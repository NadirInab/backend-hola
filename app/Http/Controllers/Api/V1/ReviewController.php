<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function __construct(private ReviewService $reviewService)
    {
    }

    /**
     * Get reviews with filtering
     * GET /api/v1/reviews
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $page = $request->query('page', 1);
        $excursionId = $request->query('excursion_id');
        $minRating = $request->query('min_rating');
        $isApproved = $request->query('is_approved');

        $query = Review::query()->with(['excursion', 'user']);

        if ($excursionId) {
            $query->where('excursion_id', $excursionId);
        }

        if ($minRating) {
            $query->where('rating', '>=', $minRating);
        }

        if (isset($isApproved)) {
            $query->where('is_approved', (bool)$isApproved);
        } else if (!auth('sanctum')->check() || !auth('sanctum')->user()->isAdmin()) {
            // Default to only approved reviews for public/non-admin users
            $query->where('is_approved', true);
        }

        $reviews = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        $pagination = [
            'total' => $reviews->total(),
            'count' => $reviews->count(),
            'per_page' => $reviews->perPage(),
            'current_page' => $reviews->currentPage(),
            'last_page' => $reviews->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ReviewResource::collection($reviews),
            $pagination,
            'Reviews retrieved successfully'
        );
    }

    /**
     * Get single review
     * GET /api/v1/reviews/{id}
     */
    public function show($id)
    {
        $review = Review::with('excursion', 'user')->find($id);

        if (!$review) {
            return $this->notFoundResponse('Review not found');
        }

        return $this->successResponse(
            new ReviewResource($review),
            'Review retrieved successfully'
        );
    }

    /**
     * Create new review
     * POST /api/v1/reviews
     */
    public function store(StoreReviewRequest $request)
    {
        $review = $this->reviewService->createReview(
            auth('sanctum')->id(),
            $request->validated()
        );

        return $this->successResponse(
            new ReviewResource($review),
            'Review submitted successfully. It will be visible once approved.',
            201
        );
    }

    /**
     * Update review
     * PUT /api/v1/reviews/{id}
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return $this->notFoundResponse('Review not found');
        }

        // Only admin can update reviews for now, or the owner (if they are logged in)
        if (!auth()->user()->isAdmin() && $review->user_id !== auth()->id()) {
            return $this->unauthorizedResponse('You do not have permission to update this review');
        }

        $review = $this->reviewService->updateReview($review, $request->validated());

        return $this->successResponse(
            new ReviewResource($review),
            'Review updated successfully'
        );
    }

    /**
     * Delete review
     * DELETE /api/v1/reviews/{id}
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return $this->notFoundResponse('Review not found');
        }

        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->unauthorizedResponse('You do not have permission to delete this review');
        }

        $this->reviewService->deleteReview($review);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ], 204);
    }
}
