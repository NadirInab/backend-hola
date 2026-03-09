<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    /**
     * Success response
     */
    protected function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Paginated success response
     */
    protected function successPaginatedResponse($items, $pagination, $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $items,
            'pagination' => $pagination,
        ], 200);
    }

    /**
     * Error response
     */
    protected function errorResponse($message = 'Error', $errors = null, $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Not found response
     */
    protected function notFoundResponse($message = 'Resource not found')
    {
        return $this->errorResponse($message, null, 404);
    }

    /**
     * Validation error response
     */
    protected function validationErrorResponse($errors)
    {
        return $this->errorResponse('Validation failed', $errors, 422);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->errorResponse($message, null, 401);
    }
}
