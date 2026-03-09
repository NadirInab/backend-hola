<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'excursion_id' => 'required|exists:excursions,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'excursion_id.required' => 'The excursion is required',
            'excursion_id.exists' => 'The selected excursion does not exist',
            'name.required' => 'Your name is required',
            'rating.required' => 'The rating is required',
            'rating.min' => 'The rating must be at least 1',
            'rating.max' => 'The rating cannot exceed 5',
            'comment.required' => 'The comment is required',
            'comment.min' => 'The comment must be at least 10 characters',
        ];
    }
}
