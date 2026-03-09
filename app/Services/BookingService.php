<?php

namespace App\Services;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Excursion;
use App\Models\Circuit;
use App\Models\Activity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Create a new booking
     */
    public function createBooking(?int $userId, array $data): Booking
    {
        // Support both legacy and polymorphic booking approaches
        $bookable = null;
        
        if (isset($data['excursion_id'])) {
            $bookable = Excursion::findOrFail($data['excursion_id']);
            $data['bookable_type'] = Excursion::class;
            $data['bookable_id'] = $bookable->id;
        } elseif (isset($data['circuit_id'])) {
            $bookable = Circuit::findOrFail($data['circuit_id']);
            $data['bookable_type'] = Circuit::class;
            $data['bookable_id'] = $bookable->id;
        } elseif (isset($data['activity_id'])) {
            $bookable = Activity::findOrFail($data['activity_id']);
            $data['bookable_type'] = Activity::class;
            $data['bookable_id'] = $bookable->id;
        } elseif (isset($data['bookable_type']) && isset($data['bookable_id'])) {
            // Direct polymorphic approach
            $modelClass = match(strtolower($data['bookable_type'])) {
                'excursion', 'App\\Models\\Excursion' => Excursion::class,
                'circuit', 'App\\Models\\Circuit' => Circuit::class,
                'activity', 'App\\Models\\Activity' => Activity::class,
                default => throw new \InvalidArgumentException('Invalid bookable type')
            };
            $bookable = $modelClass::findOrFail($data['bookable_id']);
            $data['bookable_type'] = $modelClass;
        } else {
            throw new \InvalidArgumentException('Must provide excursion_id, circuit_id, activity_id, or bookable fields');
        }

        $data['user_id'] = $userId;
        $data['status'] = Booking::STATUS_PENDING;
        
        $adults = (int)($data['number_of_adults'] ?? 1);
        $children = (int)($data['number_of_children'] ?? 0);
        
        $data['number_of_travelers'] = $adults + $children;
        
        $pricing = $this->calculateTotalPrice($bookable, $adults, $children);
        $data['total_price'] = $pricing['total'];
        $data['discount_amount'] = $pricing['discount'];
        $data['promotion_applied'] = $pricing['promotion_text'];
        
        if (isset($data['coupon_code'])) {
            $data['coupon_code'] = strtoupper($data['coupon_code']);
        }

        return Booking::create($data);
    }

    /**
     * Update a booking
     */
    public function updateBooking(Booking $booking, array $data): Booking
    {
        $originalStatus = $booking->status;

        // Recalculate total price if travelers changed
        $bookable = $booking->bookable ?? $booking->excursion;
        
        if (isset($data['number_of_adults']) || isset($data['number_of_children'])) {
            $adults = (int)($data['number_of_adults'] ?? $booking->number_of_adults);
            $children = (int)($data['number_of_children'] ?? $booking->number_of_children);
            
            $data['number_of_travelers'] = $adults + $children;
            
            $pricing = $this->calculateTotalPrice($bookable, $adults, $children);
            $data['total_price'] = $pricing['total'];
            $data['discount_amount'] = $pricing['discount'];
            $data['promotion_applied'] = $pricing['promotion_text'];
        } elseif (isset($data['number_of_travelers'])) {
            $pricing = $this->calculateTotalPrice($bookable, $data['number_of_travelers'], 0);
            $data['total_price'] = $pricing['total'];
            $data['discount_amount'] = $pricing['discount'];
            $data['promotion_applied'] = $pricing['promotion_text'];
        }

        $booking->update($data);

        // Send confirmation email if status changed to confirmed from a different status
        if (isset($data['status']) &&
            $data['status'] === Booking::STATUS_CONFIRMED &&
            $originalStatus !== Booking::STATUS_CONFIRMED) {
            try {
                Mail::to($booking->customer_email)->send(new BookingConfirmation($booking->load('bookable')));
            } catch (\Throwable $e) {
                // Log the error for investigation but do not interrupt the flow
                Log::error('Failed sending booking confirmation email', [
                    'booking_id' => $booking->id,
                    'email' => $booking->customer_email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $booking;
    }

    /**
     * Calculate total price based on excursion/circuit/activity pricing and promotions
     */
    public function calculateTotalPrice($bookable, int $adults, int $children): array
    {
        $childPrice = ($bookable->children_allowed && $bookable->children_price !== null) 
            ? $bookable->children_price 
            : $bookable->price;

        $subtotal = ($adults * $bookable->price) + ($children * $childPrice);
        $discount = 0;
        $promotionText = null;

        if ($bookable->promotion_active && $bookable->promotion_type) {
            $applied = $this->applyPromotion($subtotal, $bookable, $adults, $children);
            $discount = $applied['discount'];
            $promotionText = $applied['text'];
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount,
            'promotion_text' => $promotionText
        ];
    }

    /**
     * Apply promotion to the total price
     */
    private function applyPromotion(float $totalPrice, $bookable, int $adults, int $children): array
    {
        $type = $bookable->promotion_type;
        $value = $bookable->promotion_value;
        $discount = 0;
        $text = null;

        switch ($type) {
            case 'buy_x_get_y_free':
                $x = (int)($value['x'] ?? 2);
                $y = (int)($value['y'] ?? 1);
                $totalPeople = $adults + $children;
                
                if ($totalPeople >= ($x + $y)) {
                    $freeSets = floor($totalPeople / ($x + $y));
                    $freeCount = $freeSets * $y;
                    
                    $remainingFree = $freeCount;
                    $deduction = 0;
                    
                    // Deduct from adults first (better for user)
                    $freeAdults = min($adults, $remainingFree);
                    $deduction += $freeAdults * $bookable->price;
                    $remainingFree -= $freeAdults;
                    
                    // Deduct from children if still freebies left
                    if ($remainingFree > 0) {
                        $childPrice = ($bookable->children_allowed && $bookable->children_price !== null) 
                            ? $bookable->children_price 
                            : $bookable->price;
                        $freeChildren = min($children, $remainingFree);
                        $deduction += $freeChildren * $childPrice;
                    }
                    
                    $discount = $deduction;
                    $text = "Buy $x Get $y Free";
                }
                break;

            case 'percentage_discount':
                $percentage = (float)($value['percentage'] ?? 0);
                $discount = $totalPrice * ($percentage / 100);
                $text = "$percentage% Discount";
                break;
                
            case 'fixed_discount':
                $discount = (float)($value['discount'] ?? 0);
                $discount = min($totalPrice, $discount);
                $text = "$" . number_format($discount, 2) . " Discount";
                break;
        }

        return [
            'discount' => $discount,
            'text' => $text
        ];
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking): void
    {
        $booking->update(['status' => Booking::STATUS_CANCELLED]);
    }

    /**
     * Confirm a booking (admin only)
     */
    public function confirmBooking(Booking $booking): void
    {
        $booking->update(['status' => Booking::STATUS_CONFIRMED]);

        // Send confirmation email when booking is confirmed via this method.
        try {
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking->load('bookable')));
        } catch (\Throwable $e) {
            Log::error('Failed sending booking confirmation email (confirmBooking)', [
                'booking_id' => $booking->id,
                'email' => $booking->customer_email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
