<?php

namespace App\Http\Livewire;

use Livewire\{Component, WithPagination};
use App\Models\{TransformerBank, DistributionTransformer, FuseCutout, Circuit};

class TransformerBanks extends Component
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
    public $str_filter = 'transformer_banks.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $circuit = 0;
    public $fuse_cutout = 0;
    public $readonly = true;

    public $connection_group = "";
    public $private = false;
    public $observations = "";
    public $fuse_cutout_id = 0;
    public $circuit_id = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->circuit) {
            if ($this->fuse_cutout) {
                $transformer_banks = FuseCutout::find($this->fuse_cutout)->banks()->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
            } else {
                $fc = FuseCutout::where('circuit_id','=',$this->circuit)->get();
                $ids_array = array();
                foreach ($fc as $f) {
                    $ids_array[] = $f->id;
                }
                $transformer_banks = TransformerBank::where('fuse_cutout_id','=',$ids_array)->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
            }
        } else {
            $transformer_banks = TransformerBank::orderBy($this->str_filter, $this->updown)->paginate($this->perPage);
        }

        if ($transformer_banks->count()) {
            foreach ($transformer_banks as $bank) {
                $query = $bank->transformers->select('capacity')->orderBy('capacity', 'desc')->distinct()->get();
                $capacities = array();
                foreach ($query as $capacity) {
                    if (isset($capacities[$capacity])) {
                        $capacities[$capacity]++;
                    } else {
                        $capacities[$capacity] = 1;
                    }
                }
                $bank->capacities = $capacities;
            }
        }

        $circuits = Circuit::orderBy('circuits.name', 'asc')->get();
        if($this->circuit_id){
            $fuse_cutouts = FuseCutout::where('circuit_id','=',$this->circuit_id)->orderBy('fuse_cutouts.name', 'asc')->get();
        }else{
            $fuse_cutouts = FuseCutout::orderBy('fuse_cutouts.name', 'asc')->get();
        }

        return view('livewire.transformer-banks', ['transformer_banks' => $transformer_banks, 'circuits' => $circuits, 'fuse_cutouts' => $fuse_cutouts]);
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
        $this->connection_group = '';
        $this->private = false;
        $this->observations = "";
        $this->fuse_cutout_id = 0;
        $this->circuit_id = 0;

        $this->circuit = 0;
        $this->fuse_cutout = 0;
        $this->readonly = true;
    }

    public function toogleModal()
    {
        $this->dispatchBrowserEvent('action-modal', ['id' => 'tbModal']);
    }

    public function create()
    {
        $this->clear();
        $this->readonly = false;
        $this->toogleModal();
    }

    public function edit($item_id, $readonly)
    {
        $data = TransformerBank::find($item_id);
        $this->readonly = $readonly;
        $this->connection_group = $data->connection_group;
        $this->private = $data->private;
        $this->observations = $data->observations;
        $this->fuse_cutout_id = $data->fuse_cutout_id;

        $this->item_id = $item_id;
        $this->toogleModal();
    }

    public function save()
    {
        if (!$this->readonly) {
            $validation_array = [
                'connection_group' => 'min:0',
                'observations' => 'min:0',
                'private' => 'boolean',
                'fuse_cutout_id' => 'exists:fuse_cutouts,id',

            ];
            $this->validate($validation_array);

            if ($this->item_id) {
                $data = TransformerBank::find($this->item_id);
                $message = 'Actualizado';
            } else {
                $data = new TransformerBank();
                $message = 'Item Creado';
            }

            $data->connection_group = $this->connection_group;
            $data->observations = $this->observations;
            $data->private = $this->private;
            $data->fuse_cutout_id = $this->fuse_cutout_id;
            $data->save();

            $this->clear();

            $this->toogleModal();

            $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => $message]);
        }
        else {
            return null;
        }
    }
}
