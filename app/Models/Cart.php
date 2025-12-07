<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'status',
    ];

    //Get the user that owns the cart
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Get all items in the cart
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    //Calculate the total price of all items in cart
    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    //Get the total number of items in cart
    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}
