<?php

namespace App\Http\Livewire;

use App\Models\{Circuit, CircuitLoad};
use Livewire\{Component, WithPagination};

class CircuitLoads extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => '25'],
        'updown' => ['except' => ''],
    ];
    public $perPage = '25';
    public $search = "";
    public $updown = "";
    public $total = 0;
    public $time = 0;

    public $load = 0;
    public $datetime = "";
    public $circuit_id = 0;
    public $item_id = 0;
    public $subitem_id = 0;

    public $editing = 0;
    public $confirming = 0;
    public $confirmation = 0;
    public $name;
    public $voltage;

    public function render()
    {
        if ($this->perPage > 100) {
            $this->perPage = 100;
        }
        $start_time = microtime(true);

        $circuits = Circuit::search("%{$this->search}%")->orderBy('name', 'ASC')->paginate($this->perPage);
        $loads = CircuitLoad::whereCircuit_id($this->item_id)->orderBy('datetime', 'DESC')->take(10)->get();

        foreach ($circuits as $circuit) {
            $amp = 0;
            $loop = 0;
            $cloads = CircuitLoad::whereCircuit_id($circuit->id)->orderBy('datetime', 'DESC')->take(10)->get();
            foreach ($cloads as $cload) {
                $loop++;
                $amp += $cload->load;
            }
            if ($loop) {
                $circuit->load = $amp / $loop;
            }
        }

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        return view('livewire.circuit-loads', ['circuits' => $circuits, 'loads' => $loads]);
    }

    public function toogleModalCL($circuit_id)
    {
        $this->item_id = $circuit_id;
        $circuit = Circuit::find($circuit_id);
        $this->name = $circuit->name;
        $this->voltage = $circuit->voltage_level;
        $this->dispatchBrowserEvent('action-modal', ['id' => 'clModal']);
    }

    public function clear()
    {
        $this->perPage = '25';

        $this->load = 0;
        $this->datetime = "";
        $this->circuit_id = 0;
        $this->item_id = 0;
        $this->subitem_id = 0;
        $this->name = "";
        $this->voltage = "";
    }

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

    public function killCL($item_id)
    {
        CircuitLoad::find($item_id)->delete();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => 'Item eliminado']);
    }

    public function saveCL()
    {
        $this->validate([
            'load' => 'numeric',
            'datetime' => 'date_format:Y-m-d H:i:s',
            'circuit_id' => 'exists:circuits,id'
        ]);

        if ($this->subitem_id) {
            $data = CircuitLoad::find($this->subitem_id);
            $message = 'Actualizado';
            $data->load = $this->load;
            $data->datetime = $this->datetime;
            $data->circuit_id = $this->circuit_id;
            $data->save();

            $this->editing = 0;
            $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
        }

    }

    public function createCircuitLoad($circuit_id)
    {
        $data = new CircuitLoad();
        $data->load = 0;
        $data->circuit_id = $circuit_id;
        $data->save();
        $this->editCL($data->id);
    }

    public function editCL($subitem_id)
    {
        $data = CircuitLoad::find($subitem_id);
        $this->load = $data->load;
        $this->datetime = $data->datetime;
        $this->circuit_id = $data->circuit_id;
        $this->editing = $subitem_id;
        $this->subitem_id = $subitem_id;
    }
}
