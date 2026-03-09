<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Http\Resources\ExcursionResource;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends BaseController
{
    public function __construct(private ActivityService $activityService)
    {
    }

    /**
     * Get all activities with pagination and filtering
     * GET /api/v1/activities
     */
    public function index(Request $request)
    {
        $activities = $this->activityService->getActivities($request->all());

        $pagination = [
            'total' => $activities->total(),
            'count' => $activities->count(),
            'per_page' => $activities->perPage(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($activities),
            $pagination,
            'Activities retrieved successfully'
        );
    }

    /**
     * Get single activity with reviews
     * GET /api/v1/activities/{id}
     */
    public function show($id)
    {
        $activity = Activity::with('reviews', 'bookings', 'category', 'destination')->find($id);

        if (!$activity) {
            return $this->notFoundResponse('Activity not found');
        }

        return $this->successResponse(
            new ExcursionResource($activity),
            'Activity retrieved successfully'
        );
    }

    /**
     * Create new activity (admin only)
     * POST /api/v1/activities
     */
    public function store(StoreActivityRequest $request)
    {
        $activity = $this->activityService->createActivity($request->validated());

        return $this->successResponse(
            new ExcursionResource($activity),
            'Activity created successfully',
            201
        );
    }

    /**
     * Update activity (admin only)
     * PUT /api/v1/activities/{id}
     */
    public function update(UpdateActivityRequest $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return $this->notFoundResponse('Activity not found');
        }

        $activity = $this->activityService->updateActivity($activity, $request->validated());

        return $this->successResponse(
            new ExcursionResource($activity),
            'Activity updated successfully'
        );
    }

    /**
     * Delete activity (admin only)
     * DELETE /api/v1/activities/{id}
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return $this->notFoundResponse('Activity not found');
        }

        $this->activityService->deleteActivity($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully',
        ], 204);
    }

    /**
     * Search activities
     * GET /api/v1/activities/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'destination_id' => 'sometimes|exists:destinations,id',
            'location' => 'sometimes|string|min:3',
            'date' => 'sometimes|date',
            'people' => 'sometimes|integer|min:1',
        ]);

        $activities = $this->activityService->searchActivities($request->all());
        
        $pagination = [
            'total' => $activities->total(),
            'count' => $activities->count(),
            'per_page' => $activities->perPage(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($activities),
            $pagination,
            'Activities search results retrieved successfully'
        );
    }

    /**
     * Get suggested activities
     * GET /api/v1/activities/suggestions
     */
    public function suggestions()
    {
        $activities = $this->activityService->getSuggestedActivities();
        return $this->successResponse(
            ExcursionResource::collection($activities),
            'Suggested activities retrieved successfully'
        );
    }
}
