<?php

namespace App\Http\Controllers;

use App\Actions\Checkouts\DestroyCheckout;
use App\Actions\Checkouts\StoreCheckout;
use App\Models\Book;
use App\Models\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required'
        ]);

        (new StoreCheckout)->handle(
            auth()->user(),
            Book::findOrFail($request->book_id)
        );

        return redirect(route('dashboard'))->with('success', "Successfully checked out book.");
    }

    public function destroy(Checkout $checkout)
    {
        (new DestroyCheckout)->handle(
            auth()->user(),
            $checkout
        );

        return redirect(route('dashboard'))->with('success', "Successfully returned book.");
    }
}
