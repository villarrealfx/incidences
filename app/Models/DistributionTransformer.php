<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{TransformerBank};

class DistributionTransformer extends Model
{
    use HasFactory;

    protected $table = 'distribution_transformers';

    protected $fillable = [
        'brand',
        'serial',
        'manufacturing_year',
        'connection_group',
        'phases',
        'mounting',
        'isolation',
        'winding',
        'high_voltage',
        'low_voltage',
        'capacity',
        'bil',
        'weight',
        'transformer_bank_id',
        'installation_date',
    ];

    public function bank()
    {
        return $this->belongsTo(TransformerBank::class);
    }
}
