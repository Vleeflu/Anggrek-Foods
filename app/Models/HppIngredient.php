<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HppIngredient extends Model
{
    protected $fillable = [
        'hpp_calculation_id',
        'name',
        'quantity',
        'unit',
        'price_per_unit',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function hppCalculation(): BelongsTo
    {
        return $this->belongsTo(HppCalculation::class);
    }
}
