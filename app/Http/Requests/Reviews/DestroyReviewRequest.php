<?php

namespace App\Http\Requests\Reviews;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class DestroyReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && Review::findOrFail(request()->review_id)->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
