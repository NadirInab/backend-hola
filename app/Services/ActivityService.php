<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityService
{
    /**
     * Get a paginated list of activities with filtering and sorting.
     */
    public function getActivities(array $filters): LengthAwarePaginator
    {
        $query = Activity::with(['category', 'destination']);

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

    public function searchActivities(array $filters): LengthAwarePaginator
    {
        $query = Activity::with(['category', 'destination']);

        if (!empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (!empty($filters['people'])) {
            $query->minGroupSize($filters['people']);
        }

        return $query->paginate($filters['per_page'] ?? 20, ['*'], 'page', $filters['page'] ?? 1);
    }

    /**
     * Get suggested activities (e.g., popular or highly rated).
     */
    public function getSuggestedActivities(int $limit = 3)
    {
        return Activity::with(['category', 'destination'])
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new activity
     */
    public function createActivity(array $data): Activity
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('activities', 'public');
        }

        return Activity::create($data);
    }

    /**
     * Update an existing activity
     */
    public function updateActivity(Activity $activity, array $data): Activity
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $data['image'] = $data['image']->store('activities', 'public');
        }

        $activity->update($data);
        return $activity->fresh();
    }

    /**
     * Delete an activity
     */
    public function deleteActivity(Activity $activity): void
    {
        // Delete image if exists
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();
    }
}
