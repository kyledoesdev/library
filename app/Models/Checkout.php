<?php

namespace App\Models;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Checkout extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'checked_out_at',
        'due_at',
        'returned_at'
    ];

    public function book(): HasOne
    {
        return $this->hasOne(Book::class, 'id', 'book_id');
    }

    public function getDueAtAttribute(): string
    {
        return Carbon::parse($this->attributes['due_at'])->inUserTimezone()->format('m/d/Y');
    }
}
