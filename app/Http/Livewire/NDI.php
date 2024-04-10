<?php

namespace App\Http\Livewire;

use App\Models\Incidence;
use App\Models\Period;
use App\Models\ServiceCenter;
use Livewire\Component;

class NDI extends Component
{
    public $period_id;
    public $period;
    public $periods;
    public $centers;
    public $transmission_count = 0;
    public $distribution_count = 0;
    public $total_count = 0;
    public $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    public function mount()
    {
        $data = Period::select('*')->orderBy('periods.id', 'desc')->first();
        $this->period_id = $data->id;
    }

    public function render()
    {
        $this->period = Period::find($this->period_id);
        $this->periods = Period::select('*')->orderBy('periods.year', 'asc')->orderBy('periods.month', 'asc')->get();
        $data = Incidence::whereSystem_id(2)->wherePeriod_id($this->period_id)->get();
        $this->transmission_count = $data->count();
        $data = Incidence::whereSystem_id(1)->wherePeriod_id($this->period_id)->get();
        $this->distribution_count = $data->count();
        $data = Incidence::wherePeriod_id($this->period_id)->get();
        $this->total_count = $data->count();

        $this->centers = ServiceCenter::select('*')->orderBy('service_centers.name', 'asc')->get();

        return view('livewire.n-d-i');
    }
}
