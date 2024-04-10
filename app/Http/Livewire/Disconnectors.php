<?php

namespace App\Http\Livewire;

use Livewire\{Component, WithPagination};
use App\Models\{Disconnector, Circuit};

class Disconnectors extends Component
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
    public $str_filter = 'disconnectors.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $circuit_id = 0;
    public $readonly = true;

    public $name = 'SC-';
    public $address = '';
    public $status = false;
    public $operative = true;
    public $backbone = false;
    public $link = false;
    public $load_percentage = 0.0;
    public $distance = 0.0;
    public $observations = "";
    public $circuit_one_id = 0;
    public $circuit_two_id = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        if ($this->circuit_id) {
            $disconnectors = Disconnector::search("%{$this->search}%")->where('circuit_one_id','=',$this->circuit_id)->orWhere('circuit_two_id','=',$this->circuit_id)->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
        } else {
            $disconnectors = Disconnector::search("%{$this->search}%")->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
        }

        $circuits = Circuit::orderBy('circuits.name', 'asc')->get();

        $this->total = $disconnectors->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.disconnectors', ['disconnectors' => $disconnectors, 'circuits' => $circuits]);
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

    public function clear()
    {
        $this->name = 'SC-';
        $this->address = '';
        $this->status = false;
        $this->operative = true;
        $this->backbone = false;
        $this->link = false;
        $this->load_percentage = 0.0;
        $this->distance = 0.0;
        $this->observations = "";
        $this->circuit_one_id = 0;
        $this->circuit_two_id = 0;
        $this->circuit_id = 0;

    }

    public function toogleModal()
    {
        $this->dispatchBrowserEvent('action-modal', ['id' => 'scModal']);
    }

    public function create()
    {
        $this->clear();
        $this->readonly = false;
        if ($this->circuit_id) {
            $this->circuit_one_id = $this->circuit_id;
        }
        $this->toogleModal();
    }

    public function edit($item_id, $readonly)
    {
        $data = Disconnector::find($item_id);
        $this->readonly = $readonly;
        $this->name = $data->name;
        $this->address = $data->address;
        $this->status = $data->status;
        $this->operative = $data->operative;
        $this->backbone = $data->backbone;
        $this->link = $data->link;
        $this->load_percentage = $data->load_percentage;
        $this->distance = $data->distance;
        $this->observations = $data->observations;
        $this->circuit_one_id = $data->circuit_one_id;
        $this->circuit_two_id = $data->circuit_two_id;

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'scModal']);
    }

    public function save()
    {
        if (!$this->readonly) {
            $validation_array = [
                'name' => 'required|min:3|unique:disconnectors,name,'.$this->item_id,
                'address' => 'required|min:3',
                'circuit_one_id' => 'exists:circuits,id',
                'link' => 'boolean',
                'status' => 'boolean',
                'operative' => 'boolean',
                'backbone' => 'boolean',
                'load_percentage' => 'numeric',
                'distance' => 'numeric',

            ];
            if ($this->link) {
                $validation_array['circuit_two_id'] = 'exists:circuits,id';
            }
            $this->validate($validation_array);

            if ($this->item_id) {
                $data = Disconnector::find($this->item_id);
                $message = 'Actualizado';
            } else {
                $data = new Disconnector();
                $message = 'Item Creado';
            }

            $data->name = $this->name;
            $data->address = $this->address;
            $data->status = $this->status;
            $data->link = $this->link;
            $data->operative = $this->operative;
            $data->backbone = $this->backbone;
            $data->load_percentage = $this->load_percentage;
            $data->distance = $this->distance;
            $data->observations = $this->observations;
            $data->circuit_one_id = $this->circuit_one_id;
            $data->circuit_two_id = $this->circuit_two_id;
            $data->save();

            $this->clear();

            $this->dispatchBrowserEvent('action-modal', ['id' => 'scModal']);

            $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
        }
        else {
            return null;
        }
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
        Disconnector::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

}
