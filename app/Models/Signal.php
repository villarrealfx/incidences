<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Incidence};

class Signal extends Model
{
    use HasFactory;

    protected $table = 'signals';

    protected $fillable = [
        'name',
        'active',
    ];

    public function incidences()
    {
        return $this->belongsToMany(Incidence::class)->withTimestamps();
    }
}
