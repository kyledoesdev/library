<?php

namespace App\Actions\Checkouts;

use App\Models\Checkout;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class DestroyCheckout
{
    public function handle(User $user, Checkout $checkout)
    {
        DB::transaction(function() use ($user, $checkout) {
            /* update book quantity */
            $book = $checkout->book()->update(['quantity' => 1]);

            /* "return" book (destory checkout record) */
            $checkout->delete();
        });
    }
}