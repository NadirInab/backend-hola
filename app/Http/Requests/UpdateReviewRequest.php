<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => 'sometimes|integer|min:1|max:5',
            'title' => 'sometimes|nullable|string|max:255',
            'comment' => 'sometimes|string|min:10|max:2000',
            'is_approved' => 'sometimes|boolean',
            'name' => 'sometimes|string|max:255',
        ];
    }
}
