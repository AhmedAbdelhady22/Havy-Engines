<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Engine extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'image',
        'gallery',
        'stock_quantity',
        'sku',
        'brand',
        'horsepower',
        'displacement',
        'fuel_type',
        'condition',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    //Get the category this engine belongs to
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //Get the current price (sale price if available, otherwise regular price)
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    //Check if the engine is on sale
    public function getOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    //Check if the engine is in stock
    public function getInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

}
