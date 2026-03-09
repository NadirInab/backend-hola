<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Circuit;
use App\Http\Requests\StoreExcursionRequest;
use App\Http\Requests\UpdateExcursionRequest;
use App\Http\Resources\ExcursionResource;
use App\Services\CircuitService;
use Illuminate\Http\Request;

class CircuitController extends BaseController
{
    public function __construct(private CircuitService $circuitService)
    {
    }

    /**
     * Get all circuits with pagination and filtering
     * GET /api/v1/circuits
     */
    public function index(Request $request)
    {
        $circuits = $this->circuitService->getCircuits($request->all());

        $pagination = [
            'total' => $circuits->total(),
            'count' => $circuits->count(),
            'per_page' => $circuits->perPage(),
            'current_page' => $circuits->currentPage(),
            'last_page' => $circuits->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($circuits),
            $pagination,
            'Circuits retrieved successfully'
        );
    }

    /**
     * Get single circuit with reviews
     * GET /api/v1/circuits/{id}
     */
    public function show($id)
    {
        $circuit = Circuit::with('reviews', 'bookings', 'category', 'destination')->find($id);

        if (!$circuit) {
            return $this->notFoundResponse('Circuit not found');
        }

        return $this->successResponse(
            new ExcursionResource($circuit),
            'Circuit retrieved successfully'
        );
    }

    /**
     * Create new circuit (admin only)
     * POST /api/v1/circuits
     */
    public function store(StoreExcursionRequest $request)
    {
        $circuit = $this->circuitService->createCircuit($request->validated());

        return $this->successResponse(
            new ExcursionResource($circuit),
            'Circuit created successfully',
            201
        );
    }

    /**
     * Update circuit (admin only)
     * PUT /api/v1/circuits/{id}
     */
    public function update(UpdateExcursionRequest $request, $id)
    {
        $circuit = Circuit::find($id);

        if (!$circuit) {
            return $this->notFoundResponse('Circuit not found');
        }

        $circuit = $this->circuitService->updateCircuit($circuit, $request->validated());

        return $this->successResponse(
            new ExcursionResource($circuit),
            'Circuit updated successfully'
        );
    }

    /**
     * Delete circuit (admin only)
     * DELETE /api/v1/circuits/{id}
     */
    public function destroy($id)
    {
        $circuit = Circuit::find($id);

        if (!$circuit) {
            return $this->notFoundResponse('Circuit not found');
        }

        $this->circuitService->deleteCircuit($circuit);

        return response()->json([
            'success' => true,
            'message' => 'Circuit deleted successfully',
        ], 204);
    }

    /**
     * Search circuits
     * GET /api/v1/circuits/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'destination_id' => 'sometimes|exists:destinations,id',
            'location' => 'sometimes|string|min:3',
            'date' => 'sometimes|date',
            'people' => 'sometimes|integer|min:1',
        ]);

        $circuits = $this->circuitService->searchCircuits($request->all());
        
        $pagination = [
            'total' => $circuits->total(),
            'count' => $circuits->count(),
            'per_page' => $circuits->perPage(),
            'current_page' => $circuits->currentPage(),
            'last_page' => $circuits->lastPage(),
        ];

        return $this->successPaginatedResponse(
            ExcursionResource::collection($circuits),
            $pagination,
            'Circuits search results retrieved successfully'
        );
    }

    /**
     * Get suggested circuits
     * GET /api/v1/circuits/suggestions
     */
    public function suggestions()
    {
        $circuits = $this->circuitService->getSuggestedCircuits();
        return $this->successResponse(
            ExcursionResource::collection($circuits),
            'Suggested circuits retrieved successfully'
        );
    }
}
