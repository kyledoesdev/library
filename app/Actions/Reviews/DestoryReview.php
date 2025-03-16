<?php

namespace App\Actions\Reviews;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class DestoryReview
{
    public function handle(User $user, Review $review)
    {
        DB::transaction(function() use ($user, $review) {
            $user->reviews()->where('id', $review->getKey())->delete();
        });
    }
}