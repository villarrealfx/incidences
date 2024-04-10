<?php

namespace App\Http\Livewire;

use Livewire\{Component, WithPagination};
use App\Models\{Circuit, DistributionTransformer, FuseCutout};
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;

class DistributionTransformers extends Component
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
    public $str_filter = 'distribution_transformers.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;

    public $item_id = 0;
    public $readonly = true;

    public $brand = "";
    public $serial = "";
    public $manufacturing_year = 0;
    public $phases = '1';
    public $mounting = 'POSTE';
    public $isolation = 'LÍQUIDO';
    public $winding = 'ALUMINIO';
    public $high_voltage = 0.0;
    public $low_voltage = "";
    public $capacity = 0.0;
    public $bil = "";
    public $weight = 0.0;
    public $tap = 0;
    public $operative = true;
    public $installation_date = "";
    public $uninstall_date = "";
    public $transformer_bank_id = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $circuits = Circuit::orderBy('circuits.name', 'asc')->get();
        $cutouts = FuseCutout::orderBy('fuse_cutouts.name', 'asc')->get();

        return view('livewire.distribution-transformers', ['circuits' => $circuits, 'cutouts' => $cutouts]);
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
        $this->item_id = 0;
        $this->readonly = true;

        $this->brand = "";
        $this->serial = "";
        $this->manufacturing_year = 0;
        $this->phases = '1';
        $this->mounting = 'POSTE';
        $this->isolation = 'LÍQUIDO';
        $this->winding = 'ALUMINIO';
        $this->high_voltage = 0.0;
        $this->low_voltage = "";
        $this->capacity = 0.0;
        $this->bil = "";
        $this->weight = 0.0;
        $this->tap = 0;
        $this->operative = true;
        $this->installation_date = "";
        $this->uninstall_date = "";
        $this->transformer_bank_id = 0;
    }

    public function toogleModal()
    {
        $this->dispatchBrowserEvent('action-modal', ['id' => 'dtModal']);
    }

    public function create()
    {
        $this->clear();
        $this->readonly = false;
        $this->toogleModal();
    }

    public function edit($item_id, $readonly)
    {
        $data = DistributionTransformer::find($item_id);
        $this->readonly = $readonly;
        $this->brand = $data->brand;
        $this->serial = $data->serial;
        $this->manufacturing_year = $data->manufacturing_year;
        $this->phases = $data->phases;
        $this->mounting = $data->mounting;
        $this->isolation = $data->isolation;
        $this->winding = $data->winding;
        $this->high_voltage = $data->high_voltage;
        $this->low_voltage = $data->low_voltage;
        $this->capacity = $data->capacity;
        $this->bil = $data->bil;
        $this->weight = $data->weight;
        $this->tap = $data->tap;
        $this->operative = $data->operative;
        $this->installation_date = $data->installation_date;
        $this->uninstall_date = $data->uninstall_date;
        $this->transformer_bank_id = $data->transformer_bank_id;

        $this->item_id = $item_id;
        $this->toogleModal();
    }

    public function save()
    {
        if (!$this->readonly) {
            $validation_array = [
                'brand' => 'required|min:3',
                'serial' => 'required|min:3',
                'manufacturing_year' => 'date_format:Y',
                'phases' => 'numeric|min:1|max:3',
                'mounting' => 'in:POSTE,PEDESTAL,CÁMARA SUBTERRÁNEA',
                'isolation' => 'in:LÍQUIDO,SECO',
                'winding' => 'in:ALUMINIO,COBRE',
                'high_voltage' => 'numeric',
                'low_voltage' => 'min:3',
                'capacity' => 'numeric',
                'bil' => 'min:3',
                'weight' => 'numeric',
                'tap' => 'numeric',
                'operative' => 'boolean',
                'installation_date' => 'date',
                'uninstall_date' => 'date',
                'transformer_bank_id' => 'exists:transformer_banks,id',

            ];
            $this->validate($validation_array);

            if ($this->item_id) {
                $data = DistributionTransformer::find($this->item_id);
                $message = 'Actualizado';
            } else {
                $data = new DistributionTransformer();
                $message = 'Item Creado';
            }

            $data->brand = $this->brand;
            $data->serial = $this->serial;
            $data->manufacturing_year = $this->manufacturing_year;
            $data->phases = $this->phases;
            $data->mounting = $this->mounting;
            $data->isolation = $this->isolation;
            $data->winding = $this->winding;
            $data->high_voltage = $this->high_voltage;
            $data->low_voltage = $this->low_voltage;
            $data->capacity = $this->capacity;
            $data->bil = $this->bil;
            $data->weight = $this->weight;
            $data->tap = $this->tap;
            $data->operative = $this->operative;
            $data->installation_date = $this->installation_date;
            $data->uninstall_date = $this->uninstall_date;
            $data->transformer_bank_id = $this->transformer_bank_id;
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
