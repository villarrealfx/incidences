<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Circuit};

class CircuitLoad extends Model
{
    use HasFactory;

    protected $table = 'circuit_loads';

    protected $fillable = [
        'load',
        'datetime',
        'circuit_id',
    ];

    public function circuit()
    {
        return $this->belongsTo(Circuit::class);
    }
}
