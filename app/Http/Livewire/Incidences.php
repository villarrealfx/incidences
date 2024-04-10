<?php

namespace App\Http\Livewire;

use App\Models\Cause;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Incidence;
use App\Models\Circuit;
use App\Models\CircuitLoad;
use App\Models\Period;
use App\Models\Subcause;
use App\Models\System;
use Carbon\Carbon;

class Incidences extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => '25'],
        'updown' => ['except' => ''],
    ];
    public $search = "";
    public $perPage = '25';
    public $updown = 'asc';
    public $str_filter = 'incidences.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $modal = true;

    public $date;
    public $system_id;
    public $start;
    public $duration;
    public $load;
    public $frequency;
    public $average;
    public $tti;
    public $signal;
    public $cause_id;
    public $subcause_id;
    public $observations;
    public $circuit_id;
    public $readonly = true;

    public $circuits;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->circuits = Circuit::select('*')->orderBy('circuits.name', 'asc')->get();
        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        $incidences = Incidence::search("%{$this->search}%")
            ->orderBy($this->str_filter, $this->updown)
            ->paginate($this->perPage);

        $systems = System::orderBy('systems.name', 'asc')->get();
        $causes = Cause::orderBy('causes.name', 'asc')->get();
        $subcauses = Subcause::whereCause_id($this->cause_id)->orderBy('subcauses.name', 'asc')->get();

        $this->total = $incidences->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.incidences', ['incidences' => $incidences, 'systems' => $systems, 'causes' => $causes, 'subcauses' => $subcauses]);
    }

    public function sortData($str_filter)
    {
        $this->str_filter = $str_filter;
        if ($this->updown == 'asc') {
            $this->updown = 'desc';
        } elseif ($this->updown = 'desc') {
            $this->updown = 'asc';
        }
    }

    public function toogleModal()
    {
        $this->dispatchBrowserEvent('action-modal', ['id' => 'inModal']);
    }

    public function clear()
    {
        $this->search = "";
        $this->perPage = '25';

        $this->date = "";
        $this->system_id = 0;
        $this->start = "";
        $this->duration = "";
        $this->load = 0;
        $this->frequency = 0;
        $this->average = 0;
        $this->tti = 0;
        $this->signal = "";
        $this->cause_id = 0;
        $this->subcause_id = 0;
        $this->observations = "";
        $this->circuit_id = 0;
        $this->item_id = 0;
        $this->readonly = true;

    }

    public function create()
    {
        $this->clear();
        $this->readonly = false;
        $this->toogleModal();
    }

    /**
     * Delete
     */
    public function confirmDelete($item_id)
    {
        $this->confirming = $item_id;
    }

    public function cancelDelete($item_id)
    {
        $this->item_id = '';
        $this->confirming = '';
    }

    public function kill($item_id)
    {
        Incidence::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function save()
    {
        if (!$this->readonly) {
            $this->validate([
                'date' => 'date',
                'system_id' => 'exists:systems,id',
                'start' => 'required',
                'duration' => 'date_format:H:i:s',
                'load' => 'numeric',
                'frequency' => 'numeric',
                'average' => 'numeric',
                'tti' => 'numeric',
                'signal' => 'min:5',
                'cause_id' => 'exists:causes,id',
                'subcause_id' => 'exists:subcauses,id',
                'observations' => 'min:0',
                'circuit_id' => 'exists:circuits,id',

            ]);

            if ($this->item_id) {
                $data = Incidence::find($this->item_id);
                $message = 'Actualizado';
            } else {
                $data = new Incidence();
                $message = 'Item Creado';
            }

            $explode = explode('-', $this->date);
            $period = Period::where('month', '=', $explode[1])->where('year', '=', $explode[0])->first();
            if ($period == null) {
                $p = new Period();
                $p->month = $explode[1];
                $p->year = $explode[2];
                $p->save();
            } else {
                $p = $period;
            }

            $circuit = Circuit::find($this->circuit_id);

            $data->date = $this->date;
            $data->system_id = $this->system_id;
            $data->start = $this->start;
            $data->duration = $this->duration;
            $data->load = $this->load;
            $data->frequency = $this->frequency;
            $data->average = $this->average;
            $data->tti = $this->tti;
            $data->signal = $this->signal;
            $data->cause_id = $this->cause_id;
            $data->subcause_id = $this->subcause_id;
            $data->observations = $this->observations;
            $data->circuit_id = $circuit->id;
            $data->period_id = $p->id;
            $data->service_center_id = $circuit->serviceCenter->id;
            $data->save();

            $this->saveCircuitLoad($this->load,$this->date,$this->start,$circuit->id);

            $this->clear();

            $this->dispatchBrowserEvent('action-modal', ['id' => 'inModal']);

            $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
        }
        else {
            return null;
        }
    }

    public function saveCircuitLoad($load, $date, $start, $circuit_id)
    {
        $datetime = Carbon::parse($date.' '.$start)->format('Y-m-d H:i:s');
        $circuit_load = CircuitLoad::whereDatetime($datetime)->whereCircuit_id($circuit_id)->first();
        if (!$circuit_load) {
            $circuit_load = new CircuitLoad();
        }
        $circuit_load->load = $load;
        $circuit_load->datetime = $datetime;
        $circuit_load->circuit_id = $circuit_id;
        $circuit_load->save();
    }

    /**
     * editar
     */
    public function edit($item_id, $readonly)
    {
        $data = Incidence::find($item_id);
        $this->readonly = $readonly;
        $this->date = $data->date;
        $this->system_id = $data->system_id;
        $this->start = $data->start;
        $this->duration = $data->duration;
        $this->load = $data->load;
        $this->frequency = $data->frequency;
        $this->average = $data->average;
        $this->tti = $data->tti;
        $this->signal = $data->signal;
        $this->cause_id = $data->cause_id;
        $this->subcause_id = $data->subcause_id;
        $this->observations = $data->observations;
        $this->circuit_id = $data->circuit_id;

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'inModal']);
    }
}
