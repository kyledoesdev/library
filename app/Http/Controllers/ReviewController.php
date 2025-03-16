<?php

namespace App\Http\Controllers;

use App\Actions\Reviews\DestoryReview;
use App\Actions\Reviews\StoreReview;
use App\Actions\Reviews\UpdateReview;
use App\Http\Requests\Reviews\DestroyReviewRequest;
use App\Http\Requests\Reviews\StoreReviewRequest;
use App\Http\Requests\Reviews\UpdateReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function create()
    {
        return Inertia::render('reviews/Create', [
            'book_id' => request()->book_id
        ]);
    }

    public function store(StoreReviewRequest $request)
    {
        (new StoreReview)->handle(auth()->user(), $request->all());

        return redirect(route('book.show', [
            'book' => $request->book_id
        ]))->with('success', "Reviewed created successfuly.");
    }

    public function edit(Review $review)
    {
        return Inertia::render('reviews/Edit', [
            'review' => $review
        ]);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        (new UpdateReview)->handle(auth()->user(), $review, $request->validated());

        return redirect(route('book.show', [
            'book' => $review->book_id
        ]))->with('success', "Reviewed updated successfuly.");
    }

    public function destroy(DestroyReviewRequest $request, Review $review)
    {
        $book_id = $review->book_id;

        (new DestoryReview)->handle(auth()->user(), $review);

        return redirect(route('book.show', [
            'book' => $book_id
        ]))->with('success', "Reviewed updated successfuly.");
    }
}
