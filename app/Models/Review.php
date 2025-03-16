<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'stars',
        'body'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('default_order', fn ($query) => $query->latest());
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
