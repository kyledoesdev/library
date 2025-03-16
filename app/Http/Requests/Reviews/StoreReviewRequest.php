<?php

namespace App\Http\Requests\Reviews;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && Book::findOrFail(request()->book_id)->checkout->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'book_id' => 'required',
            'stars' => 'required',
            'body' => 'required'
        ];
    }
}
