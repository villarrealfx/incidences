<?php

namespace App\Http\Livewire;

use Livewire\{Component, WithPagination};
use App\Models\{FuseCutout, Circuit};

class FuseCutouts extends Component
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
    public $str_filter = 'fuse_cutouts.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $circuit_id = 0;
    public $readonly = true;

    public $name = 'CC-';
    public $address = '';
    public $status = false;
    public $operative = true;
    public $fuse = 0;
    public $observations = "";
    public $circuit_one_id = 0;

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
            $fuse_cutouts = FuseCutout::search("%{$this->search}%")->where('circuit_id','=',$this->circuit_id)->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
        } else {
            $fuse_cutouts = FuseCutout::search("%{$this->search}%")->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
        }

        $circuits = Circuit::orderBy('circuits.name', 'asc')->get();

        $this->total = $fuse_cutouts->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.fuse-cutouts', ['fuse_cutouts' => $fuse_cutouts, 'circuits' => $circuits]);
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
        $this->name = 'CC-';
        $this->address = '';
        $this->status = false;
        $this->operative = true;
        $this->fuse = 0;
        $this->observations = "";
        $this->circuit_one_id = 0;
        $this->circuit_id = 0;

    }

    public function toogleModal()
    {
        $this->dispatchBrowserEvent('action-modal', ['id' => 'ccModal']);
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
        $data = FuseCutout::find($item_id);
        $this->readonly = $readonly;
        $this->name = $data->name;
        $this->address = $data->address;
        $this->status = $data->status;
        $this->operative = $data->operative;
        $this->fuse = $data->fuse;
        $this->observations = $data->observations;
        $this->circuit_one_id = $data->circuit_id;

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'ccModal']);
    }

    public function save()
    {
        if (!$this->readonly) {
            $validation_array = [
                'name' => 'required|min:3|unique:disconnectors,name,'.$this->item_id,
                'address' => 'required|min:3',
                'circuit_one_id' => 'exists:circuits,id',
                'status' => 'boolean',
                'operative' => 'boolean',
                'fuse' => 'numeric',

            ];
            $this->validate($validation_array);

            if ($this->item_id) {
                $data = FuseCutout::find($this->item_id);
                $message = 'Actualizado';
            } else {
                $data = new FuseCutout();
                $message = 'Item Creado';
            }

            $data->name = $this->name;
            $data->address = $this->address;
            $data->status = $this->status;
            $data->operative = $this->operative;
            $data->fuse = $this->fuse;
            $data->observations = $this->observations;
            $data->circuit_id = $this->circuit_one_id;
            $data->save();

            $this->clear();

            $this->dispatchBrowserEvent('action-modal', ['id' => 'ccModal']);

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
        FuseCutout::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }
}
