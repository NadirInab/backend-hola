<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Excursion;

class ReviewService
{
    /**
     * Create a new review
     */
    public function createReview(?int $userId, array $data): Review
    {
        if ($userId) {
            $data['user_id'] = $userId;
        }
        
        // New reviews are not approved by default if we want moderation
        // But the user said "immediately if no moderation"
        // I'll set it to false for now, Admin will approve it.
        $data['is_approved'] = false;

        $review = Review::create($data);

        // Update excursion rating and reviews count (only for approved reviews usually)
        // But maybe we want to update it now? Or only after approval?
        // Usually, average rating only considers approved reviews.
        $this->updateExcursionRating($data['excursion_id']);

        return $review;
    }

    /**
     * Update a review
     */
    public function updateReview(Review $review, array $data): Review
    {
        $excursionId = $review->excursion_id;
        $review->update($data);

        // Update excursion rating
        $this->updateExcursionRating($excursionId);

        return $review;
    }

    /**
     * Delete a review
     */
    public function deleteReview(Review $review): void
    {
        $excursionId = $review->excursion_id;
        $review->delete();

        // Update excursion rating
        $this->updateExcursionRating($excursionId);
    }

    /**
     * Update excursion's average rating and review count
     */
    private function updateExcursionRating(int $excursionId): void
    {
        $excursion = Excursion::find($excursionId);
        
        if ($excursion) {
            $reviews = $excursion->reviews()->where('is_approved', true)->get();
            
            $excursion->update([
                'rating' => $reviews->count() > 0 ? $reviews->avg('rating') : 0,
                'reviews_count' => $reviews->count(),
            ]);
        }
    }
}
