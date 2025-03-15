<?php

namespace App\Models;

use App\Models\Model;

class Author extends Model
{
    protected $fillable = [
        'first_name',
        'last_name'
    ];

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
