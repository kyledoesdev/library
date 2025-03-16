<?php

namespace App\Models;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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

    protected $appends = [
        'formatted_published_at'
    ];

    public function checkout(): HasOne
    {
        return $this->hasOne(Checkout::class);
    }

    public function author(): HasOne
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getFormattedPublishedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['published_at'])->inUserTimezone()->format('m/d/Y');
    }

    public function getPageCountAttribute(): string
    {
        return number_format($this->attributes['page_count']);
    }

    public function getCoverAttribute()
    {
        if (Str::startsWith($this->attributes['cover'], 'https')) {
            return $this->attributes['cover'];
        }

        return asset('storage/' . $this->attributes['cover']);
    }

    public static function forLibrarian()
    {
        return self::query()
            ->when(request()->search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->with('checkout', 'author')
            ->paginate(request()->input('per_page', 15))
            ->withQueryString();
    }

    public static function forCustomer()
    {
        return self::query()
            ->whereHas('checkout', function($query2) {
                $query2->where('user_id', auth()->id());
            })
            ->when(request()->search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->with('checkout', 'author')
            ->paginate(request()->input('per_page', 15))
            ->withQueryString();
    }

    public static function featured()
    {
        return self::query()
            ->whereDoesntHave('checkout')
            ->when(request()->featured_search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when(! request()->featured_search, function($query) {
                $query->inRandomOrder();
            })
            ->with('checkout', 'author')
            ->limit(15)
            ->get();
    }
}
