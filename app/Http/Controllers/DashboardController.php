<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $books = auth()->user()->is_librarian
            ? Book::forLibrarian()
            : Book::forCustomer();

        return Inertia::render('Dashboard', [
            'books' => $books,
            'featured_books' => Book::featured()
        ]);
    }
}
