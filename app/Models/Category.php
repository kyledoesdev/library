<?php

namespace App\Models;

use App\Models\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('default_order', fn($query) => $query->orderBy('name', 'asc'));
    }
}
