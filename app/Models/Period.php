<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incidence;

class Period extends Model
{
    use HasFactory;

    protected $table = 'periods';

    protected $fillable = [
        'month',
        'year',
    ];

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('month','LIKE',"%$keyword%")
            ->orwhere('year','LIKE',"%$keyword%")
            ;
    }
}
