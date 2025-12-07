<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'engine_id',
        'engine_name',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    
     //Get the order this item belongs to
     
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    //Get the engine (may be null if engine was deleted)
    public function engine(): BelongsTo
    {
        return $this->belongsTo(Engine::class);
    }
}
