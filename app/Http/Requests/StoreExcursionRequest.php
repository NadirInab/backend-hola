<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExcursionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:excursions',
            'description' => 'required|string|min:50',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'duration' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'group_size' => 'required|string|max:255',
            'languages' => 'required|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'included' => 'nullable|array',
            'included.*' => 'string|max:255',
            'not_included' => 'nullable|array',
            'not_included.*' => 'string|max:255',
            'itinerary' => 'nullable|array',
            'itinerary.*.time' => 'string|max:10',
            'itinerary.*.activity' => 'string|max:255',
            'children_allowed' => 'nullable|boolean',
            'children_price' => 'nullable|numeric|min:0|max:99999999.99',
            'promotion_type' => 'nullable|string|max:255',
            'promotion_value' => 'nullable|array',
            'promotion_active' => 'nullable|boolean',
            'type' => 'required|in:activity,circuit',
            'availability' => 'nullable|array',
            'availability.*' => 'string',
            'pickup_times' => 'nullable|array',
            'pickup_times.*' => 'string',
            'price_adult_eur' => 'nullable|numeric|min:0|max:99999999.99',
            'price_child_eur' => 'nullable|numeric|min:0|max:99999999.99',
        ];
    }

    protected function prepareForValidation()
    {
        // Decode JSON string fields from FormData
        $jsonFields = ['promotion_value', 'availability', 'pickup_times', 'included', 'not_included', 'itinerary'];
        foreach ($jsonFields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $decoded = json_decode($this->input($field), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->merge([$field => $decoded]);
                }
            }
        }

        // Cast numeric fields from FormData strings to appropriate types
        $numericFields = ['price', 'children_price', 'price_adult_eur', 'price_child_eur', 'rating', 'reviews_count'];
        foreach ($numericFields as $field) {
            if ($this->has($field) && $this->input($field) !== null && $this->input($field) !== '') {
                $castType = $field === 'reviews_count' ? 'int' : 'float';
                $this->merge([$field => is_numeric($this->input($field)) ? ($castType === 'int' ? (int)$this->input($field) : (float)$this->input($field)) : $this->input($field)]);
            }
        }
        
        // Handle boolean fields from FormData (often sent as "1" or "0" or "true"/"false" strings)
        if ($this->has('children_allowed')) {
            $this->merge([
                'children_allowed' => filter_var($this->children_allowed, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
        if ($this->has('promotion_active')) {
            $this->merge([
                'promotion_active' => filter_var($this->promotion_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The excursion title is required',
            'title.unique' => 'This excursion title already exists',
            'description.required' => 'The description is required',
            'description.min' => 'The description must be at least 50 characters',
            'price.required' => 'The price is required',
            'price.min' => 'The price must be greater than 0',
            'category_id.required' => 'The category is required',
            'category_id.exists' => 'The selected category does not exist',
        ];
    }
}
