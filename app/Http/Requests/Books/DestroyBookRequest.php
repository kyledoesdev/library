<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class DestroyBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_librarian;
    }

    public function rules(): array
    {
        return [
            
        ];
    }
}
