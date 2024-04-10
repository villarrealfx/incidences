<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Incidence;
use Carbon\Carbon;

class ManualOperations extends Component
{
    public $now;
    public $start_date;
    public $finish_date;
    public $incidences;

    public function mount()
    {
        $this->now = Carbon::now()->format('Y-m-d');
        $this->start_date = Carbon::now()->format('Y-m-d');
        $this->finish_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $this->incidences = (new Incidence)->manualOperations()->whereBetween('date', [$this->start_date, $this->finish_date])->where('subcause_id', '!=', 31);
        return view('livewire.manual-operations');
    }
}
