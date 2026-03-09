<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Support both legacy excursion_id and new polymorphic bookable fields
            'excursion_id' => 'sometimes|exists:excursions,id',
            'circuit_id' => 'sometimes|exists:circuits,id',
            'activity_id' => 'sometimes|exists:activities,id',
            'bookable_type' => 'sometimes|in:excursion,circuit,activity',
            'bookable_id' => 'sometimes|integer|min:1',
            
            'booking_date' => 'required|date|after_or_equal:today',
            'number_of_adults' => 'required|integer|min:1|max:100',
            'number_of_children' => 'nullable|integer|min:0|max:100',
            'number_of_travelers' => 'nullable|integer|min:1|max:200',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'traveler_details' => 'nullable|array',
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'excursion_id.required' => 'The excursion is required',
            'excursion_id.exists' => 'The selected excursion does not exist',
            'booking_date.required' => 'The booking date is required',
            'booking_date.after_or_equal' => 'The booking date must be today or in the future',
            'number_of_adults.required' => 'At least 1 adult is required',
            'customer_name.required' => 'The customer name is required',
            'customer_email.required' => 'The customer email is required',
            'customer_phone.required' => 'The customer phone is required',
        ];
    }
}
