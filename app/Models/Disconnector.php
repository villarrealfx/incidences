<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Circuit};

class Disconnector extends Model
{
    use HasFactory;

    protected $table = 'disconnectors';

    protected $fillable = [
        'name',
        'address',
        'status',
        'operative',
        'backbone',
        'link',
        'load_percentage',
        'distance',
        'observations',
        'circuit_one_id',
        'circuit_two_id',
    ];

    public function circuit()
    {
        return $this->belongsTo(Circuit::class, 'circuit_one_id');
    }

    public function circuitTwo()
    {
        return $this->belongsTo(Circuit::class, 'circuit_two_id');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('id','LIKE',"%$keyword%")
            ->orwhere('name','LIKE',"%$keyword%")
            ->orwhere('address','LIKE',"%$keyword%")
            ->orwhere('observations','LIKE',"%$keyword%")
            ;
    }
}
