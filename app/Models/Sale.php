<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'hpp_calculation_id',
        // ...existing code...
        'sale_date',
        'quantity_sold',
        'selling_price_used',
        'cost_per_unit',
        'total_revenue',
        'total_cost',
        'profit',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'quantity_sold' => 'integer',
        'selling_price_used' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function hppCalculation()
    {
        return $this->belongsTo(HppCalculation::class);
    }

    // branch relation removed
}
