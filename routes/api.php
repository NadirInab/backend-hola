<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ExcursionController;
use App\Http\Controllers\Api\V1\CircuitController;
use App\Http\Controllers\Api\V1\ActivityController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PromotionController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DestinationController;

Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('destinations', [DestinationController::class, 'index']);
    Route::get('destinations/{id}', [DestinationController::class, 'show']);
    
    // NOTE: These must be defined BEFORE the resource show route (excursions/{id}, circuits/{id}, activities/{id})
    // to avoid "search"/"suggestions" being treated as an {id}.
    Route::get('excursions/search', [ExcursionController::class, 'search']);
    Route::get('excursions/suggestions', [ExcursionController::class, 'suggestions']);
    Route::get('circuits/search', [CircuitController::class, 'search']);
    Route::get('circuits/suggestions', [CircuitController::class, 'suggestions']);
    Route::get('activities/search', [ActivityController::class, 'search']);
    Route::get('activities/suggestions', [ActivityController::class, 'suggestions']);

    Route::apiResource('excursions', ExcursionController::class, [
        'only' => ['index', 'show']
    ]);

    Route::apiResource('circuits', CircuitController::class, [
        'only' => ['index', 'show']
    ]);

    Route::apiResource('activities', ActivityController::class, [
        'only' => ['index', 'show']
    ]);

    Route::apiResource('categories', CategoryController::class, [
        'only' => ['index', 'show']
    ]);

    Route::get('excursions/{id}/reviews', [ExcursionController::class, 'reviews']);
    
    Route::apiResource('reviews', ReviewController::class, [
        'only' => ['index', 'show', 'store']
    ]);

    Route::post('bookings', [BookingController::class, 'store']);

    Route::get('promotions', [PromotionController::class, 'index']);

    // Authentication routes
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth routes
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/profile', [AuthController::class, 'profile']);
        Route::put('auth/profile', [AuthController::class, 'updateProfile']);

        // User bookings
        Route::apiResource('bookings', BookingController::class, [
            'except' => ['store']
        ]);

        // User reviews
        Route::apiResource('reviews', ReviewController::class, [
            'only' => ['update', 'destroy']
        ]);

        // Admin routes
        Route::middleware('admin')->group(function () {
            Route::apiResource('excursions', ExcursionController::class, [
                'only' => ['store', 'update', 'destroy']
            ]);

            Route::apiResource('circuits', CircuitController::class, [
                'only' => ['store', 'update', 'destroy']
            ]);

            Route::apiResource('activities', ActivityController::class, [
                'only' => ['store', 'update', 'destroy']
            ]);

            Route::apiResource('categories', CategoryController::class, [
                'only' => ['store', 'update', 'destroy']
            ]);

            // Destinations CRUD (Admin only)
            Route::post('destinations', [DestinationController::class, 'store']);
            Route::put('destinations/{id}', [DestinationController::class, 'update']);
            Route::delete('destinations/{id}', [DestinationController::class, 'destroy']);

            Route::post('promotions', [PromotionController::class, 'store']);
            Route::put('promotions/{id}', [PromotionController::class, 'update']);
            Route::get('dashboard-stats', [DashboardController::class, 'stats']);

            // Company Settings (Admin only)
            Route::get('company-settings', [App\Http\Controllers\CompanySettingController::class, 'index']);
            Route::post('company-settings', [App\Http\Controllers\CompanySettingController::class, 'store']);
        });
    });
});