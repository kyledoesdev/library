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
        'quantity',
        'is_featured',
    ];

    protected $appends = [
        'formatted_published_at',
        'rating'
    ];

    public function casts(): array
    {
        return [
            'is_featured' => 'boolean'
        ];
    }

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

    /* Attrbiutes */

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

    public function getRatingAttribute()
    {
        $reviews = $this->reviews;

        if (count($reviews) == 0) {
            return 'N/A';
        }

        $stars = $reviews->sum('stars');

        return number_format($stars / count($reviews));
    }

    /* scopes & queries */

    public static function forLibrarian()
    {
        return self::query()
            ->selectRaw('
                books.*,
                CONCAT(authors.first_name, " ", authors.last_name) as author_full_name,
                checkouts.checked_out_at
            ')
            ->withSearchAndSort(false)
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
            ->selectRaw('
                books.*,
                CONCAT(authors.first_name, " ", authors.last_name) as author_full_name,
                checkouts.checked_out_at
            ')
            ->whereHas('checkout', function($query2) {
                $query2->where('user_id', auth()->id());
            })
            ->withSearchAndSort(false)
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

    public static function getFeaturedBooks()
    {
        return self::query()
            ->selectRaw('
                books.*,
                CONCAT(authors.first_name, " ", authors.last_name) as author_full_name
            ')
            ->where('is_featured', true)
            ->withSearchAndSort(true)
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

    public function scopeWithSearchAndSort($query, $isFeatured)
    {
        $query->newQuery()
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->leftJoin('checkouts', function($join) {
                $join->on('books.id', '=', 'checkouts.book_id');
            })
            ->when(request()->search, function($query, $search) {
                $query->where('books.title', 'like', "%{$search}%");
            })
            ->when(request()->featured_search, function($query, $search) {
                $query->where('books.title', 'like', "%{$search}%");
            })
            ->when($isFeatured, function($query) {
                $query->newQuery()
                    ->where('is_featured', true)
                    ->when(request()->featured_order === 'title' && request()->featured_direction, function($q) {
                        $q->orderBy('books.title', request()->featured_direction);
                    })
                    ->when(request()->featured_order === 'author' && request()->featured_direction, function($q) {
                        $q->orderByRaw('CONCAT(authors.first_name, " ", authors.last_name) ' . request()->featured_direction);
                    })
                    ->when(request()->featured_order === 'availability' && request()->featured_direction, function($q) {
                        if (request()->featured_direction === 'asc') {
                            $q->orderByRaw('CASE WHEN checkouts.checked_out_at IS NULL THEN 0 ELSE 1 END');
                        } else {
                            $q->orderByRaw('CASE WHEN checkouts.checked_out_at IS NULL THEN 1 ELSE 0 END');
                        }
                    });
            })
            ->when(! $isFeatured, function($query) {
                $query
                    ->when(request()->order === 'title' && request()->direction, function($q) {
                        $q->orderBy('books.title', request()->direction);
                    })
                    ->when(request()->order === 'author' && request()->direction, function($q) {
                        $q->orderByRaw('CONCAT(authors.first_name, " ", authors.last_name) ' . request()->direction);
                    })
                    ->when(request()->order === 'availability' && request()->direction, function($q) {
                        if (request()->direction === 'asc') {
                            $q->orderByRaw('CASE WHEN checkouts.checked_out_at IS NULL THEN 0 ELSE 1 END');
                        } else {
                            $q->orderByRaw('CASE WHEN checkouts.checked_out_at IS NULL THEN 1 ELSE 0 END');
                        }
                    })
                    ->when(!request()->order, function($q) {
                        $q->orderBy('books.title', 'asc'); // Default to title ASC for regular table
                    });
            });
    }
}
