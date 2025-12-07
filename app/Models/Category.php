<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    //Get all engines in this category
    public function engines(): HasMany
    {
        return $this->hasMany(Engine::class);
    }

    public function activeEngines(): HasMany
    {
        return $this->hasMany(Engine::class)->where('is_active', true);
    }


}
