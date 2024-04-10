<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Circuit, TransformerBank};

class FuseCutout extends Model
{
    use HasFactory;

    protected $table = 'fuse_cutouts';

    protected $fillable = [
        'name',
        'address',
        'status',
        'operative',
        'fuse',
        'observations',
        'circuit_id',
    ];

    public function circuit()
    {
        return $this->belongsTo(Circuit::class);
    }

    public function banks()
    {
        return $this->hasMany(TransformerBank::class);
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
