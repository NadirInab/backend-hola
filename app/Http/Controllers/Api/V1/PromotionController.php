<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends BaseController
{
    /**
     * Get the current promotion.
     * GET /api/v1/promotions
     */
    public function index()
    {
        $promotion = Promotion::first();

        if (!$promotion) {
            // Automatically create a default one if none exists
            $promotion = Promotion::create([
                'text' => '✨ Summer Sale! Get 20% off on all tours.',
                'ctaText' => 'Book Now',
                'ctaLink' => '/excursions',
                'enabled' => false,
            ]);
        }

        return $this->successResponse(
            $promotion,
            'Promotion retrieved successfully'
        );
    }

    /**
     * Update the promotion.
     * PUT /api/v1/promotions/{id} or POST /api/v1/promotions
     */
    public function store(Request $request)
    {
        return $this->updateOrCreate($request);
    }

    public function update(Request $request, $id)
    {
        return $this->updateOrCreate($request);
    }

    private function updateOrCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
            'ctaText' => 'nullable|string',
            'ctaLink' => 'nullable|string',
            'enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $promotion = Promotion::first();

        if ($promotion) {
            $promotion->update($request->all());
        } else {
            $promotion = Promotion::create($request->all());
        }

        return $this->successResponse(
            $promotion,
            'Promotion updated successfully'
        );
    }
}
