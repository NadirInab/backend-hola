<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends BaseController
{
    public function __construct(private BookingService $bookingService)
    {
    }

    /**
     * Get user bookings
     * GET /api/v1/bookings
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 50);
        $page = $request->query('page', 1);
        $status = $request->query('status');

        $user = auth()->user();
        $query = $user->isAdmin() ? Booking::with(['excursion', 'user']) : $user->bookings()->with('excursion');

        $query->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate($perPage, ['*'], 'page', $page);

        $pagination = [
            'total' => $bookings->total(),
            'count' => $bookings->count(),
            'per_page' => $bookings->perPage(),
            'current_page' => $bookings->currentPage(),
            'last_page' => $bookings->lastPage(),
        ];

        return $this->successPaginatedResponse(
            BookingResource::collection($bookings),
            $pagination,
            'Bookings retrieved successfully'
        );
    }

    /**
     * Get single booking
     * GET /api/v1/bookings/{id}
     */
    public function show($id)
    {
        $booking = Booking::with('excursion', 'user')->find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->unauthorizedResponse('You do not have permission to view this booking');
        }

        return $this->successResponse(
            new BookingResource($booking),
            'Booking retrieved successfully'
        );
    }

    /**
     * Create new booking
     * POST /api/v1/bookings
     */
    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->createBooking(
            auth()->id(),
            $request->validated()
        );

        return $this->successResponse(
            new BookingResource($booking),
            'Booking created successfully',
            201
        );
    }

    /**
     * Update booking status
     * PUT /api/v1/bookings/{id}
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->unauthorizedResponse('You do not have permission to update this booking');
        }

        $booking = $this->bookingService->updateBooking($booking, $request->validated());

        return $this->successResponse(
            new BookingResource($booking),
            'Booking updated successfully'
        );
    }

    /**
     * Cancel booking
     * DELETE /api/v1/bookings/{id}
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->unauthorizedResponse('You do not have permission to cancel this booking');
        }

        $this->bookingService->cancelBooking($booking);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
        ], 204);
    }
}
