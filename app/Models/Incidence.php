<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Circuit, Period, ServiceCenter, Cause, Subcause, Process, Signal, System};

class Incidence extends Model
{
    use HasFactory;

    protected $table = 'incidences';

    protected $fillable = [
        'date',
        'start',
        'duration',
        'load',
        'frequency',
        'average',
        'tti',
        'signal',
        'observations',
        'circuit_id',
        'period_id',
        'service_center_id',
        'finish',
        'active',
        'operation',
        'cause_id',
        'subcause_id',
        'process_id',
        'system_id',
    ];

    protected $guarded = [];

    public function circuit()
    {
        return $this->belongsTo(Circuit::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function serviceCenter()
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function subcause()
    {
        return $this->belongsTo(Subcause::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function signals()
    {
        return $this->belongsToMany(Signal::class)->withTimestamps();
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('date','LIKE',"%$keyword%")
            ->orwhere('signal','LIKE',"%$keyword%")
            ->orwhere('observations','LIKE',"%$keyword%")
            ;
    }

    public function manualOperations()
    {
        return Incidence::where('observations', 'LIKE', '%POSITIVO, ABIERT%')
                          ->orWhere('observations', 'LIKE', '%POSITIVO. ABIERT%')
                          ->orderBy('date')
                          ->orderBy('start')
                          ->get();
    }

    public function pac($from, $to)
    {
        return Incidence::where('subcause_id', '=', 31)
                        ->whereBetween('date', [$from, $to])
                        ->orderBy('date')
                        ->orderBy('start')
                        ->get();
    }
}

