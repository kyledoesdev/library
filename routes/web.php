<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/book', BookController::class);
    Route::resource('/checkout', CheckoutController::class);
    Route::resource('/review', ReviewController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
