<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{FuseCutout, DistributionTransformer};

class TransformerBank extends Model
{
    use HasFactory;

    protected $table = 'transformer_banks';

    protected $fillable = [
        'connection_group',
        'private',
        'fuse_cutout_id',
    ];

    public function fuseCutout()
    {
        return $this->belongsTo(FuseCutout::class);
    }

    public function transformers()
    {
        return $this->hasMany(DistributionTransformer::class);
    }
}
