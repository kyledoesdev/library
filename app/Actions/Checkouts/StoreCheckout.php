<?php

namespace App\Actions\Checkouts;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class StoreCheckout
{
    public function handle(User $user, Book $book)
    {
        DB::transaction(function() use ($user, $book) {
            /* create checkout */
            $user->checkouts()->create([
                'book_id' => $book->getKey(),
                'checked_out_at' => now(),
                'due_at' => now()->addDays(5)
            ]);

            /* update book quantity */
            $book->update(['quantity' => 0]);
        });
    }
}