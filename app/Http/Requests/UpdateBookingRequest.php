<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|in:pending,confirmed,cancelled',
            'booking_date' => 'sometimes|date|after_or_equal:today',
            'number_of_adults' => 'sometimes|integer|min:1|max:100',
            'number_of_children' => 'sometimes|integer|min:0|max:100',
            'number_of_travelers' => 'sometimes|integer|min:1|max:200',
            'traveler_details' => 'sometimes|nullable|array',
            'notes' => 'sometimes|nullable|string|max:1000',
        ];
    }
}
