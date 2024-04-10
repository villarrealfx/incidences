<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Incidence};

class Process extends Model
{
    use HasFactory;

    protected $table = 'processes';

    protected $fillable = [
        'name',
    ];

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }
}
