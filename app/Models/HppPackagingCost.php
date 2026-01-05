<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HppPackagingCost extends Model
{
    protected $fillable = [
        'hpp_calculation_id',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function hppCalculation(): BelongsTo
    {
        return $this->belongsTo(HppCalculation::class);
    }
}
