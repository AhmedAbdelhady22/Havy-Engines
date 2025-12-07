<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
        protected $fillable = [
        'cart_id',
        'engine_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    //Get the cart this item belongs to
     
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    
     //Get the engine for this cart item
     
    public function engine(): BelongsTo
    {
        return $this->belongsTo(Engine::class);
    }

    //Calculate the subtotal for this item (price × quantity)
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
