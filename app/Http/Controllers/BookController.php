<?php

namespace App\Http\Controllers;

use App\Actions\Books\StoreBook;
use App\Actions\Books\UpdateBook;
use App\Http\Requests\Books\DestroyBookRequest;
use App\Http\Requests\Books\StoreBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function create()
    {
        return Inertia::render('books/Create', [
            'categories' => Category::all(),
            'authors' => Author::all()
        ]);
    }

    public function store(StoreBookRequest $request)
    {
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $cover = $request->file('cover')->store('book-covers', 'public');
        }

        (new StoreBook)->handle($request->all(), $cover ?? null);

        return redirect(route('dashboard'))->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        return Inertia::render('books/Show', [
            'book' => $book->load([
                'author',
                'category',
                'checkout',
                'checkout.user',
                'reviews',
                'reviews.user'
            ]),
        ]);
    }

    public function edit(Book $book)
    {
        return Inertia::render('books/Edit', [
            'book' => $book,
            'categories' => Category::all(),
            'authors' => Author::all()
        ]);
    }

    public function update(Request $request, Book $book)
    {
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $cover = $request->file('cover')->store('book-covers', 'public');
        }

        (new UpdateBook)->handle($book, $request->all(), $cover ?? $book->cover);

        return redirect(route('dashboard'))->with('success', 'Book updated successfully!');
    }

    public function destroy(DestroyBookRequest $request, Book $book)
    {
        $book->delete();

        return redirect(route('dashboard'))->with('success', 'Book deleted successfully!');
    }
}
