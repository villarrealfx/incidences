<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Substation, ServiceCenter, Incidence, FuseCutout, Disconnector, CircuitLoad};

class Circuit extends Model
{
    use HasFactory;

    protected $table = 'circuits';

    protected $fillable = [
        'name',
        'voltage_level',
        'substation_id',
        'service_center_id',
        'breaker',
        'load',
        'status',
        'route',
        'priority',
        'attended',
        'day',
        'night',
        'parent_id',
    ];

    public function substation()
    {
        return $this->belongsTo(Substation::class);
    }

    public function serviceCenter()
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function disconnectors()
    {
        return (new Disconnector())->where('circuit_one_id', '=', $this->id)->orwhere('circuit_two_id', '=', $this->id);
    }

    public function fuseCutouts()
    {
        return $this->hasMany(FuseCutout::class);
    }

    public function loads()
    {
        return $this->hasMany(CircuitLoad::class);
    }

    public function parent()
    {
        $this->belongsTo(Circuit::class, 'parent_id');
    }

    public function children()
    {
        $this->hasMany(Circuit::class, 'parent_id');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('name','LIKE',"%$keyword%")
            ->orwhere('voltage_level','LIKE',"%$keyword%")
            ->orwhere('breaker','LIKE',"%$keyword%")
            ;
    }
}
