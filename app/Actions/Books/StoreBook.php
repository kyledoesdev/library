<?php

namespace App\Actions\Books;

use App\Models\Author;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class StoreBook
{
    public function handle(array $attributes, string $cover)
    {
        DB::transaction(function() use ($attributes, $cover) {
            $author = null;

            if (isset($attributes['new_author']['first_name']) && isset($attributes['new_author']['last_name'])) {
                $author = Author::create([
                    'first_name' => $attributes['new_author']['first_name'],
                    'last_name' => $attributes['new_author']['last_name']
                ])->getKey();
            }
            
            Book::create([
                'author_id' => is_null($author) ? $attributes['author_id'] : $author,
                'category_id' => $attributes['category_id'],
                'title' => $attributes['title'],
                'description' => $attributes['description'],
                'publisher' => $attributes['publisher'],
                'published_at' => Carbon::parse($attributes['published_at'])->toDateTimeString(),
                'isbn' => $attributes['isbn'],
                'page_count' => $attributes['page_count'],
                'cover' => is_null($cover)
                    ? "https://picsum.photos/seed/{$attributes['title']}/200/300"
                    : $cover
            ]);
        });
    }
}