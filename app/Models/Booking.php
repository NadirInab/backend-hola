<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'excursion_id',
        'circuit_id',
        'activity_id',
        'bookable_id',
        'bookable_type',
        'user_id',
        'booking_date',
        'number_of_adults',
        'number_of_children',
        'number_of_travelers',
        'customer_name',
        'customer_email',
        'customer_phone',
        'traveler_details',
        'total_price',
        'discount_amount',
        'promotion_applied',
        'coupon_code',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'number_of_adults' => 'integer',
        'number_of_children' => 'integer',
        'number_of_travelers' => 'integer',
        'traveler_details' => 'array',
        'total_price' => 'float',
        'discount_amount' => 'float',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the bookable model (Excursion, Circuit, or Activity).
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the excursion associated with the booking (for backwards compatibility).
     */
    public function excursion(): BelongsTo
    {
        return $this->belongsTo(Excursion::class);
    }

    /**
     * Get the user associated with the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope to get bookings by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Calculate total price based on travelers and excursion price
     */
    public function calculateTotalPrice(): float
    {
        return $this->excursion->price * $this->number_of_travelers;
    }
}
