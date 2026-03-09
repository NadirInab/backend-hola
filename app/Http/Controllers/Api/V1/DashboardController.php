<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Booking;
use App\Models\Excursion;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    /**
     * Get dashboard statistics.
     * GET /api/v1/dashboard-stats
     */
    public function stats()
    {
        $totalBookings = Booking::count();
        $totalExcursions = Excursion::count();
        $totalUsers = User::where('role', 'customer')->count();
        // Total revenue must be computed from confirmed bookings only.
        // We use the `confirmed` scope so the calculation is executed by the database
        // (no caching or local aggregation) to guarantee integrity.
        // If future refund/payment columns are added, refine this query to exclude them.
        $totalRevenue = (float) Booking::confirmed()->sum('total_price');
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        return response()->json([
            'totalBookings' => $totalBookings,
            'totalExcursions' => $totalExcursions,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
        ]);
    }
}
