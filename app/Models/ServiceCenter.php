<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Circuit, Incidence};

class ServiceCenter extends Model
{
    use HasFactory;

    protected $table = 'service_centers';

    protected $fillable = [
        'name',
        'type',
    ];

    public function circuits()
    {
        return $this->hasMany(Circuit::class);
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('name','LIKE',"%$keyword%")
            ;
    }
}
