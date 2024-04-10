<?php

namespace App\Http\Livewire;

use App\Models\{System};
use Livewire\{Component, WithPagination};

class Systems extends Component
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
    public $str_filter = 'systems.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $name;
    public $active;
    
    public function render()
    {
        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        $systems = System::search("%{$this->search}%")
            ->orderBy($this->str_filter, $this->updown)
            ->paginate($this->perPage);

        $this->total = $systems->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.systems', ['systems' => $systems]);
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
        $this->dispatchBrowserEvent('action-modal', ['id' => 'syModal']);
    }

    public function clear()
    {
        $this->search = "";
        $this->perPage = '25';

        $this->name = "";
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
        System::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|max:150',
        ]);

        if ($this->item_id) {
            $data = System::find($this->item_id);
            $message = 'Actualizado';
        } else {
            $data = new System();
            $message = 'Item Creado';
        }

        $data->name = $this->name;
        $data->save();

        $this->clear();

        $this->dispatchBrowserEvent('action-modal', ['id' => 'syModal']);

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
    }

    /**
     * editar
     */
    public function edit($item_id)
    {
        $data = System::find($item_id);
        $this->name = $data->name;

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'syModal']);
    }

    public function toggleActive($item_id)
    {
        $data = System::find($item_id);
        $data->active = !$data->active;
        $data->save();
    }
}
