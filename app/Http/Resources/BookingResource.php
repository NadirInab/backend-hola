<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'excursionId' => $this->excursion_id,
            'excursion' => new ExcursionResource($this->whenLoaded('excursion')),
            'userId' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'bookingDate' => $this->booking_date,
            'numberOfAdults' => $this->number_of_adults,
            'numberOfChildren' => $this->number_of_children,
            'numberOfTravelers' => $this->number_of_travelers,
            'customerName' => $this->customer_name,
            'customerEmail' => $this->customer_email,
            'customerPhone' => $this->customer_phone,
            'travelerDetails' => $this->traveler_details,
            'totalPrice' => $this->total_price,
            'discountAmount' => $this->discount_amount,
            'promotionApplied' => $this->promotion_applied,
            'couponCode' => $this->coupon_code,
            'status' => $this->status,
            'notes' => $this->notes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
