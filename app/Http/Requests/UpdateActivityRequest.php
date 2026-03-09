<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        // Get activity ID from route - {activity} is the route parameter name from apiResource
        $activityId = $this->route('activity') ?? null;
        
        return [
            'title' => 'sometimes|string|max:255|unique:activities,title,' . ($activityId ?? ''),
            'description' => 'sometimes|nullable|string|min:10',
            'price' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'duration' => 'sometimes|nullable|string|max:255',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'destination_id' => 'sometimes|nullable|exists:destinations,id',
            'group_size' => 'sometimes|nullable|string|max:255',
            'languages' => 'sometimes|nullable|string',
            'rating' => 'sometimes|nullable|numeric|min:1|max:5',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'sometimes|nullable|numeric|between:-90,90',
            'longitude' => 'sometimes|nullable|numeric|between:-180,180',
            'included' => 'sometimes|nullable|array',
            'included.*' => 'string|max:255',
            'not_included' => 'sometimes|nullable|array',
            'not_included.*' => 'string|max:255',
            'itinerary' => 'sometimes|nullable|array',
            'itinerary.*.time' => 'string|max:10',
            'itinerary.*.activity' => 'string|max:255',
            'children_allowed' => 'sometimes|nullable|boolean',
            'children_price' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'promotion_type' => 'sometimes|nullable|string|max:255',
            'promotion_value' => 'sometimes|nullable|array',
            'promotion_active' => 'sometimes|nullable|boolean',
            'type' => 'sometimes|in:activity,circuit',
            'availability' => 'sometimes|nullable|array',
            'availability.*' => 'string',
            'pickup_times' => 'sometimes|nullable|array',
            'pickup_times.*' => 'string',
            'price_adult_eur' => 'sometimes|nullable|numeric|min:0|max:99999999.99',
            'price_child_eur' => 'sometimes|nullable|numeric|min:0|max:99999999.99',
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
        $numericFields = ['price', 'children_price', 'price_adult_eur', 'price_child_eur', 'rating'];
        foreach ($numericFields as $field) {
            if ($this->has($field) && $this->input($field) !== null && $this->input($field) !== '') {
                $this->merge([$field => is_numeric($this->input($field)) ? (float)$this->input($field) : $this->input($field)]);
            }
        }

        // Convert FormData boolean strings to actual booleans
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

        // Normalize empty string values to null for optional fields to avoid "present but empty" validation failures
        $nullableFields = [
            'description', 'duration', 'group_size', 'languages', 'price', 'children_price',
            'promotion_type', 'price_adult_eur', 'price_child_eur',
        ];

        foreach ($nullableFields as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $this->merge([$field => null]);
            }
        }
    }
}
