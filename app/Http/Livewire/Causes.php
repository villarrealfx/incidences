<?php

namespace App\Http\Livewire;

use Livewire\{Component, WithPagination};
use App\Models\{Cause, Subcause};

class Causes extends Component
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
    public $str_filter = 'causes.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $editing = 0;
    public $confirmation = 0;
    public $item_id = 0;
    public $subitem_id = 0;

    public $name;
    public $active;
    public $scheduled;
    public $sc_name;

    public function render()
    {
        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        $causes = Cause::search("%{$this->search}%")
            ->orderBy($this->str_filter, $this->updown)
            ->paginate($this->perPage);

        $this->total = $causes->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        $subcauses = Subcause::whereCause_id($this->item_id)->orderBy('id', 'asc')->get();

        return view('livewire.causes', ['causes' => $causes, 'subcauses' => $subcauses]);
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
        $this->dispatchBrowserEvent('action-modal', ['id' => 'csModal']);
    }

    public function toogleModalSC($cause_id)
    {
        $this->item_id = $cause_id;
        $cause = Cause::find($cause_id);
        $this->name = $cause->name;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'scModal']);
    }

    public function clear()
    {
        $this->search = "";
        $this->perPage = '25';

        $this->name = "";
        $this->item_id = 0;
        $this->subitem_id = 0;
    }

    /**
     * Delete
     */
    public function confirmDelete($item_id)
    {
        $this->confirming = $item_id;
    }

    public function confirmKilling($item_id)
    {
        $this->confirmation = $item_id;
    }

    public function cancelDelete($item_id)
    {
        $this->item_id = '';
        $this->confirming = '';
    }

    public function cancelKilling($item_id)
    {
        $this->subitem_id = '';
        $this->confirmation = '';
    }

    public function cancelEditing($item_id)
    {
        $this->subitem_id = '';
        $this->editing = '';
    }

    public function kill($item_id)
    {
        Cause::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function killSC($item_id)
    {
        Subcause::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|max:150',
        ]);

        if ($this->item_id) {
            $data = Cause::find($this->item_id);
            $message = 'Actualizado';
        } else {
            $data = new Cause();
            $message = 'Item Creado';
        }

        $data->name = $this->name;
        $data->save();

        $this->clear();

        $this->dispatchBrowserEvent('action-modal', ['id' => 'csModal']);

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
    }

    public function saveSC()
    {
        $this->validate([
            'sc_name' => 'required|min:3|max:150',
        ]);

        if ($this->subitem_id) {
            $data = subcause::find($this->subitem_id);
            $message = 'Actualizado';
        }

        $data->name = $this->sc_name;
        $data->save();
        $this->editing = 0;

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
    }

    public function createSubcause($cause_id)
    {
        $data = new Subcause();
        $data->name = "Nueva Subcausa " . $data->id;
        $data->active = true;
        $data->cause_id = $cause_id;
        $data->save();
        $this->editSC($data->id);
    }

    /**
     * editar
     */
    public function edit($item_id)
    {
        $data = Cause::find($item_id);
        $this->name = $data->name;

        $this->item_id = $item_id;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'csModal']);
    }

    public function editSC($subitem_id)
    {
        $data = Subcause::find($subitem_id);
        $this->sc_name = $data->name;
        $this->editing = $subitem_id;
        $this->subitem_id = $subitem_id;
    }

    public function toggleActive($item_id)
    {
        $data = Cause::find($item_id);
        $data->active = !$data->active;
        $data->save();
    }

    public function toggleScheduled($item_id)
    {
        $data = Cause::find($item_id);
        $data->scheduled = !$data->scheduled;
        $data->save();
    }

    public function toggleSubcause($item_id)
    {
        $data = Subcause::find($item_id);
        $data->active = !$data->active;
        $data->save();
    }
}
