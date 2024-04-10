<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Circuit;

class Substation extends Model
{
    use HasFactory;
    protected $table = 'substations';

    protected $fillable = [
        'name',
        'level',
        'voltage_level',
    ];

    public function circuits()
    {
        return $this->hasMany(Circuit::class);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('name','LIKE',"%$keyword%")
            ->orwhere('level','LIKE',"%$keyword%")
            ->orwhere('voltage_level','LIKE',"%$keyword%")
            ;
    }
}
