<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Subcause, Incidence};

class Cause extends Model
{
    use HasFactory;

    protected $table = 'causes';

    protected $fillable = [
        'name',
        'active',
        'scheduled',
    ];

    public function subcauses()
    {
        return $this->hasMany(Subcause::class);
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
