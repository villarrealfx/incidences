<?php

namespace App\Http\Livewire;

use App\Models\{Period, System};
use Livewire\{Component, WithPagination};

class Periods extends Component
{
    use WithPagination;

    public $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    public $search = "";
    public $perPage = '25';
    public $updown = 'asc';
    public $str_filter = 'periods.id';
    public $total = 0;
    public $time = 0;
    public $confirming = 0;
    public $item_id = 0;

    public $month;
    public $year;

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

        $periods = Period::search("%{$this->search}%")->orderBy($this->str_filter, $this->updown)->paginate($this->perPage);

        $this->total = $periods->count();

        $end_time = microtime(true);
        $this->time = $end_time - $start_time;

        $systems = System::get();

        return view('livewire.periods', ['periods' => $periods, 'systems' => $systems]);
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
}
