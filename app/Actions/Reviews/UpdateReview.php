<?php

namespace App\Actions\Reviews;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class UpdateReview
{
    public function handle(User $user, Review $review, array $attributes)
    {
        DB::transaction(function() use ($user, $review, $attributes) {
            /* update review */
            $user->reviews()->where('id', $review->getKey())->update([
                'stars' => $attributes['stars'],
                'body' => $attributes['body']
            ]);
        });
    }
}