<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DestinationController extends BaseController
{
    /**
     * Get all destinations (with optional filters)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Destination::query()->withCount('excursions');

            // Filter by active status if requested
            if ($request->has('active_only') && $request->active_only) {
                $query->active();
            }

            $destinations = $query->ordered()->get();

            return $this->successResponse(
                DestinationResource::collection($destinations),
                'Destinations retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve destinations', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get single destination
     */
    public function show($id): JsonResponse
    {
        try {
            $destination = Destination::withCount('excursions')->findOrFail($id);

            return $this->successResponse(
                new DestinationResource($destination),
                'Destination retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->notFoundResponse('Destination not found');
        }
    }

    /**
     * Create new destination
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:destinations,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $destination = Destination::create($validator->validated());

            return $this->successResponse(
                new DestinationResource($destination),
                'Destination created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create destination', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update destination
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $destination = Destination::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:destinations,name,' . $id,
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'is_active' => 'boolean',
                'display_order' => 'integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $destination->update($validator->validated());

            return $this->successResponse(
                new DestinationResource($destination),
                'Destination updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update destination', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete destination
     */
    public function destroy($id): JsonResponse
    {
        try {
            $destination = Destination::findOrFail($id);
            
            // Check if destination has excursions
            if ($destination->excursions()->count() > 0) {
                return $this->errorResponse(
                    'Cannot delete destination with associated excursions',
                    ['excursions_count' => $destination->excursions()->count()],
                    400
                );
            }

            $destination->delete();

            return $this->successResponse(
                null,
                'Destination deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete destination', ['error' => $e->getMessage()], 500);
        }
    }
}
