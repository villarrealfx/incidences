<?php

namespace App\Http\Livewire;

use App\Models\Incidence;
use App\Models\Period;
use App\Models\ServiceCenter;
use Livewire\Component;

class NDIYear extends Component
{
    public $year;
    public $years;
    public $centers;
    public $transmission_count = 0;
    public $distribution_count = 0;
    public $total_count = 0;

    public function mount()
    {
        $this->years = Period::select('year')->orderBy('periods.year', 'asc')->distinct()->get();
        foreach ($this->years as $index) {
            $this->year = $index->year;
        }
    }

    public function render()
    {
        $this->years = Period::select('year')->orderBy('periods.year', 'asc')->distinct()->get();
        $this->transmission_count = Incidence::whereSystem_id(2)->whereBetween('date', [$this->year.'-01-01', $this->year.'-12-31'])->count();
        $this->distribution_count = Incidence::whereSystem_id(1)->whereBetween('date', [$this->year.'-01-01', $this->year.'-12-31'])->count();
        $this->total_count = Incidence::whereBetween('date', [$this->year.'-01-01', $this->year.'-12-31'])->count();

        $this->centers = ServiceCenter::select('*')->orderBy('service_centers.name', 'asc')->get();

        return view('livewire.n-d-i-year');
    }
}
