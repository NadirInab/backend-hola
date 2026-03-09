<?php

namespace App\Services;

use App\Models\Excursion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExcursionService
{
    /**
     * Get a paginated list of excursions with filtering and sorting.
     */
    public function getExcursions(array $filters): LengthAwarePaginator
    {
        $query = Excursion::with(['category', 'destination']);

        // Apply filters using model scopes
        if (!empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $query->priceRange($filters['min_price'], $filters['max_price']);
        }

        // Sorting (minimal + safe defaults)
        $sortBy = $filters['sort_by'] ?? null;
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'price') {
            $query->orderBy('price', $sortOrder);
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', $sortOrder);
        } else {
            // Default: show best-rated first
            $query->orderBy('rating', 'desc');
        }

        return $query->paginate($filters['per_page'] ?? 20, ['*'], 'page', $filters['page'] ?? 1);
    }

    public function searchExcursions(array $filters): LengthAwarePaginator
    {
        $query = Excursion::with(['category', 'destination']);

        if (!empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (!empty($filters['people'])) {
            $query->minGroupSize($filters['people']);
        }
        
        // The 'date' filter is not used for backend filtering as per the decision to not change the schema.
        // It will be used by the frontend for display.

        return $query->paginate($filters['per_page'] ?? 20, ['*'], 'page', $filters['page'] ?? 1);
    }

    /**
     * Get suggested excursions (e.g., popular or highly rated).
     */
    public function getSuggestedExcursions(int $limit = 3)
    {
        // For now, let's return the top 3 rated excursions as suggestions.
        return Excursion::with(['category', 'destination'])
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new excursion
     */
    public function createExcursion(array $data): Excursion
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('excursions', 'public');
        }

        return Excursion::create($data);
    }

    /**
     * Update an existing excursion
     */
    public function updateExcursion(Excursion $excursion, array $data): Excursion
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($excursion->image && Storage::disk('public')->exists($excursion->image)) {
                Storage::disk('public')->delete($excursion->image);
            }
            $data['image'] = $data['image']->store('excursions', 'public');
        }

        $excursion->update($data);
        return $excursion->fresh();
    }

    /**
     * Delete an excursion
     */
    public function deleteExcursion(Excursion $excursion): void
    {
        // Delete image if exists
        if ($excursion->image && Storage::disk('public')->exists($excursion->image)) {
            Storage::disk('public')->delete($excursion->image);
        }

        $excursion->delete();
    }
}
