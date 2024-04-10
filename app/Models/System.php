<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Incidence};

class System extends Model
{
    use HasFactory;

    protected $table = 'systems';

    protected $fillable = [
        'name',
        'active',
    ];

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
