<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Cause, Incidence};

class Subcause extends Model
{
    use HasFactory;

    protected $table = 'subcauses';

    protected $fillable = [
        'name',
        'active',
        'cause_id',
    ];

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }
}
