<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    /**
     * Get all categories.
     * GET /api/v1/categories
     */
    public function index()
    {
        $categories = Category::all();
        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    /**
     * Get single category.
     * GET /api/v1/categories/{id}
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        return $this->successResponse(
            new CategoryResource($category),
            'Category retrieved successfully'
        );
    }

    /**
     * Create new category (admin only).
     * POST /api/v1/categories
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create($request->all());

        return $this->successResponse(
            new CategoryResource($category),
            'Category created successfully',
            201
        );
    }

    /**
     * Update category (admin only).
     * PUT /api/v1/categories/{id}
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->all());

        return $this->successResponse(
            new CategoryResource($category),
            'Category updated successfully'
        );
    }

    /**
     * Delete category (admin only).
     * DELETE /api/v1/categories/{id}
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        // Optional: Check if category has excursions
        if ($category->excursions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Category cannot be deleted because it has associated excursions'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], 204);
    }
}
