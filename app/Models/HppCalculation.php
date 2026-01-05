<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HppCalculation extends Model
{
    protected $fillable = [
        'user_id',
        // ...existing code...
        'product_id',
        'total_ingredients_cost',
        'total_packaging_cost',
        'labor_cost',
        'portions',
        'total_cost',
        'hpp_per_portion',
        'profit_margin_percent',
        'profit_amount',
        'selling_price',
        'notes',
    ];

    protected $casts = [
        'total_ingredients_cost' => 'decimal:2',
        'total_packaging_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'hpp_per_portion' => 'decimal:2',
        'profit_margin_percent' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // branch relation removed

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(HppIngredient::class);
    }

    public function packagingCosts(): HasMany
    {
        return $this->hasMany(HppPackagingCost::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
