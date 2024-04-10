<?php

namespace App\Http\Livewire;

use Livewire\{Component,WithPagination};
use App\Models\{Circuit,ServiceCenter,Substation,Incidence};

class Circuits extends Component
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
    public $str_filter = 'circuits.name';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $name;
    public $voltage_level = "13.8";
    public $substation_id;
    public $service_center_id;
    public $breaker;
    public $load;
    public $status;
    public $route;
    public $priority;
    public $day;
    public $night;
    public $parent_id = 0;


    public $centers;
    public $substations;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->centers = ServiceCenter::select('*')->orderBy('service_centers.name', 'asc')->get();
        $this->substations = Substation::select('*')->orderBy('substations.name', 'asc')->get();

        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        $circuits = Circuit::search("%{$this->search}%")
            ->orderBy($this->str_filter, $this->updown)
            ->paginate($this->perPage);

        $cs = Circuit::orderBy('name', 'ASC')->get();

        $this->total = $circuits->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.circuits', ['circuits' => $circuits, 'cs' => $cs]);
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
        $this->dispatchBrowserEvent('action-modal', ['id' => 'crModal']);
    }

    public function tooglePriority($item_id)
    {
        $data = Circuit::find($item_id);
        $data->priority = !$data->priority;
        $data->save();
    }

    public function toogleAttention($item_id)
    {
        $data = Circuit::find($item_id);
        $data->attended = !$data->attended;
        $data->save();
    }

    public function toogleDay($item_id)
    {
        $data = Circuit::find($item_id);
        $data->day = !$data->day;
        $data->save();
    }

    public function toogleNight($item_id)
    {
        $data = Circuit::find($item_id);
        $data->night = !$data->night;
        $data->save();
    }

    public function clear()
    {
        $this->search = "";
        $this->perPage = '25';

        $this->name = "";
        $this->voltage_level = "";
        $this->item_id = 0;
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
        Circuit::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function save()
    {
        $validation_array = [
            'name' => 'required|min:3|max:150',
            'voltage_level' => 'required|min:3|max:150',
            'breaker' => 'min:3|max:5',
            'substation_id' => 'exists:substations,id',
            'service_center_id' => 'exists:service_centers,id',
        ];
        if ($this->parent_id) {
            $validation_array['parent_id'] = 'exists:circuits,id';
        }
        else {
            $this->parent_id = null;
        }

        $this->validate($validation_array);

        if ($this->item_id) {
            $data = Circuit::find($this->item_id);
            $message = 'Actualizado';
        } else {
            $data = new Circuit();
            $message = 'Item Creado';
        }

        $data->name = $this->name;
        $data->voltage_level = $this->voltage_level;
        $data->substation_id = $this->substation_id;
        $update_incidences = false;
        if ($data->service_center_id != $this->service_center_id) {
            $update_incidences = true;
        }
        $data->service_center_id = $this->service_center_id;
        $data->breaker = $this->breaker;
        $data->parent_id = $this->parent_id;

        $data->save();

        if ($update_incidences) {
            $incidences = Incidence::whereCircuit_id($this->item_id)->get();
            foreach ($incidences as $incidence) {
                $incidence->service_center_id = $this->service_center_id;
                $incidence->save();
            }
        }

        $this->clear();

        $this->dispatchBrowserEvent('action-modal', ['id' => 'crModal']);

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
    }

    /**
     * editar
     */
    public function edit($item_id)
    {
        $data = Circuit::find($item_id);
        $this->name = $data->name;
        $this->voltage_level = $data->voltage_level;
        $this->substation_id = $data->substation_id;
        $this->service_center_id = $data->service_center_id;
        $this->breaker = $data->breaker;
        if ($data->parent_id) {
            $this->parent_id = $data->parent_id;
        } else {
            $this->parent_id = 0;
        }

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'crModal']);
    }
}
