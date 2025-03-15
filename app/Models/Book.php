<?php

namespace App\Models;

use App\Models\Model;
use Carbon\Carbon;

class Book extends Model
{
    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'description',
        'cover',
        'publisher',
        'published_at',
        'isbn',
        'page_count',
        'quantity'
    ];

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function author()
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function getPublishedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['published_at'])->inUserTimezone()->format('m/d/Y');
    }

    public function getPageCountAttribute(): string
    {
        return number_format($this->attributes['page_count']);
    }
}
