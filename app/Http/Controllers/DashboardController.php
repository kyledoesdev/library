<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Dashboard', [
            'books' => Book::query()
                ->when($request->search, function($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->with('checkouts', 'author')
                ->paginate($request->input('per_page', 15))
                ->withQueryString(),
        ]);
    }
}
