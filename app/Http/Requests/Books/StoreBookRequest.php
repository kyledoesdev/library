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
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'publisher' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'isbn' => 'nullable|string|max:20|unique:books',
            'page_count' => 'nullable|integer|min:1',
            'cover' => 'nullable|image|max:2048', // 2MB max
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
        ];

        if (request()->has('new_author.first_name') && request()->has('new_author.last_name')) {
            $rules = array_merge($rules, [
                'new_author.first_name' => 'required|string|max:255',
                'new_author.last_name' => 'required|string|max:255',
            ]);
        } else {
            $rules = array_merge($rules, [
                'author_id' => 'required'
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for the book.',
            'title.max' => 'The book title cannot exceed 255 characters.',
            
            'isbn.unique' => 'This ISBN is already registered with another book.',
            'isbn.max' => 'ISBN cannot exceed 20 characters.',
            
            'page_count.integer' => 'Page count must be a whole number.',
            'page_count.min' => 'Page count must be at least 1.',
            
            'cover.image' => 'The cover must be an image file (jpg, png, etc).',
            'cover.max' => 'The cover image cannot exceed 2MB.',
            
            'published_at.date' => 'Please enter a valid publication date.',
            
            'category_id.required' => 'Please select a category for this book.',
            'category_id.exists' => 'The selected category does not exist.',
            
            'author_id.required_without' => 'Please either select an existing author or add a new one.',
            'author_id.exists' => 'The selected author does not exist.',
            
            'new_author.first_name.required_without' => 'Please enter the author\'s first name.',
            'new_author.last_name.required_without' => 'Please enter the author\'s last name.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'author_id' => 'author',
            'page_count' => 'page count',
            'published_at' => 'publication date',
            'new_author.first_name' => 'author first name',
            'new_author.last_name' => 'author last name',
            'is_featured' => 'featured status',
        ];
    }
}