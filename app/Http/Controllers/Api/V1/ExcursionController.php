<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Excursion;
use App\Http\Requests\StoreExcursionRequest;
use App\Http\Requests\UpdateExcursionRequest;
use App\Http\Resources\ExcursionResource;
use App\Services\ExcursionService;
use Illuminate\Http\Request;

class ExcursionController extends BaseController
{
    public function __construct(private ExcursionService $excursionService)
    {
    }

    /**
     * Get all excursions with pagination and filtering
     * GET /api/v1/excursions
     */
    public function index(Request $request)
    {
        $excursions = $this->excursionService->getExcursions($request->all());

        $pagination = [
            'total' => $excursions->total(),
            'count' => $excursions->count(),
            'per_page' => $excursions->perPage(),
            'current_page' => $excursions->currentPage(),
            'last_page' => $excursions->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($excursions),
            $pagination,
            'Excursions retrieved successfully'
        );
    }

    /**
     * Get single excursion with reviews
     * GET /api/v1/excursions/{id}
     */
    public function show($id)
    {
        $excursion = Excursion::with('reviews', 'bookings', 'category', 'destination')->find($id);

        if (!$excursion) {
            return $this->notFoundResponse('Excursion not found');
        }

        return $this->successResponse(
            new ExcursionResource($excursion),
            'Excursion retrieved successfully'
        );
    }

    /**
     * Create new excursion (admin only)
     * POST /api/v1/excursions
     */
    public function store(StoreExcursionRequest $request)
    {
        $excursion = $this->excursionService->createExcursion($request->validated());

        return $this->successResponse(
            new ExcursionResource($excursion),
            'Excursion created successfully',
            201
        );
    }

    /**
     * Update excursion (admin only)
     * PUT /api/v1/excursions/{id}
     */
    public function update(UpdateExcursionRequest $request, $id)
    {
        $excursion = Excursion::find($id);

        if (!$excursion) {
            return $this->notFoundResponse('Excursion not found');
        }

        $excursion = $this->excursionService->updateExcursion($excursion, $request->validated());

        return $this->successResponse(
            new ExcursionResource($excursion),
            'Excursion updated successfully'
        );
    }

    /**
     * Delete excursion (admin only)
     * DELETE /api/v1/excursions/{id}
     */
    public function destroy($id)
    {
        $excursion = Excursion::find($id);

        if (!$excursion) {
            return $this->notFoundResponse('Excursion not found');
        }

        $this->excursionService->deleteExcursion($excursion);

        return response()->json([
            'success' => true,
            'message' => 'Excursion deleted successfully',
        ], 204);
    }

    /**
     * Search excursions
     * GET /api/v1/excursions/search
     */
    public function search(Request $request)
    {
        $request->validate([
            // Destination search is primarily driven by destination_id; location is optional
            // (kept for backwards/UX purposes).
            'destination_id' => 'sometimes|exists:destinations,id',
            'location' => 'sometimes|string|min:3',
            'date' => 'sometimes|date',
            'people' => 'sometimes|integer|min:1',
        ]);

        $excursions = $this->excursionService->searchExcursions($request->all());
        
        $pagination = [
            'total' => $excursions->total(),
            'count' => $excursions->count(),
            'per_page' => $excursions->perPage(),
            'current_page' => $excursions->currentPage(),
            'last_page' => $excursions->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($excursions),
            $pagination,
            'Excursions search results retrieved successfully'
        );
    }

    /**
     * Get suggested excursions
     * GET /api/v1/excursions/suggestions
     */
    public function suggestions()
    {
        $excursions = $this->excursionService->getSuggestedExcursions();
        return $this->successResponse(
            ExcursionResource::collection($excursions),
            'Suggested excursions retrieved successfully'
        );
    }


}
