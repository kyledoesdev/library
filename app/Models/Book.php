<?php

namespace App\Models;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'formatted_published_at',
        'rating'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('default_order', function ($query) {
            $query->when(auth()->user()->is_librarian, fn($q) => $q->orderBy('title', 'asc'));
        });
    }

    public function checkout(): HasOne
    {
        return $this->hasOne(Checkout::class, 'book_id', 'id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
            ->with([
                'checkout',
                'checkout.user',
                'author',
                'reviews',
                'reviews.user'
            ])
            ->paginate(15)
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
            ->with([
                'checkout',
                'checkout.user',
                'author',
                'reviews',
                'reviews.user'
            ])
            ->paginate(15)
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
            ->with([
                'checkout',
                'checkout.user',
                'author',
                'reviews',
                'reviews.user'
            ])
            ->limit(15)
            ->get();
    }

    public function getRatingAttribute()
    {
        $reviews = $this->reviews;

        if (count($reviews) == 0) {
            return 'N/A';
        }

        $stars = $reviews->sum('stars');

        return number_format($stars / count($reviews));
    }
}
