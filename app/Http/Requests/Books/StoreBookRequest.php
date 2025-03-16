<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_librarian;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'isbn' => 'nullable|string|max:20|unique:books',
            'page_count' => 'nullable|integer|min:1',
            'cover' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
