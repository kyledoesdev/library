<?php

namespace App\Models;

use App\Models\Model;
use Carbon\Carbon;

class Checkout extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'checked_out_at',
        'due_at'
    ];

    public function getDueAtAttribute(): string
    {
        return Carbon::parse($this->attributes['due_at'])->inUserTimezone()->format('m/d/Y');
    }
}
