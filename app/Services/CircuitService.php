<?php

namespace App\Services;

use App\Models\Circuit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CircuitService
{
    /**
     * Get a paginated list of circuits with filtering and sorting.
     */
    public function getCircuits(array $filters): LengthAwarePaginator
    {
        $query = Circuit::with(['category', 'destination']);

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

    public function searchCircuits(array $filters): LengthAwarePaginator
    {
        $query = Circuit::with(['category', 'destination']);

        if (!empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (!empty($filters['people'])) {
            $query->minGroupSize($filters['people']);
        }

        return $query->paginate($filters['per_page'] ?? 20, ['*'], 'page', $filters['page'] ?? 1);
    }

    /**
     * Get suggested circuits (e.g., popular or highly rated).
     */
    public function getSuggestedCircuits(int $limit = 3)
    {
        return Circuit::with(['category', 'destination'])
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new circuit
     */
    public function createCircuit(array $data): Circuit
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('circuits', 'public');
        }

        return Circuit::create($data);
    }

    /**
     * Update an existing circuit
     */
    public function updateCircuit(Circuit $circuit, array $data): Circuit
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($circuit->image && Storage::disk('public')->exists($circuit->image)) {
                Storage::disk('public')->delete($circuit->image);
            }
            $data['image'] = $data['image']->store('circuits', 'public');
        }

        $circuit->update($data);
        return $circuit->fresh();
    }

    /**
     * Delete a circuit
     */
    public function deleteCircuit(Circuit $circuit): void
    {
        // Delete image if exists
        if ($circuit->image && Storage::disk('public')->exists($circuit->image)) {
            Storage::disk('public')->delete($circuit->image);
        }

        $circuit->delete();
    }
}
