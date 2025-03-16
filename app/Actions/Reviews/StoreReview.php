<?php

namespace App\Actions\Reviews;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class StoreReview
{
    public function handle(User $user, array $attributes)
    {
        DB::transaction(function() use ($user, $attributes) {
            /* create the review for the book for the user */

            auth()->user()->reviews()->create([
                'book_id' => $attributes['book_id'],
                'stars' => $attributes['stars'],
                'body' => $attributes['body']
            ]);
        });
    }
}