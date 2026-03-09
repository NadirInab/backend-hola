<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExcursionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'categoryId' => $this->category_id,
            'destination' => new DestinationResource($this->whenLoaded('destination')),
            'destinationId' => $this->destination_id,
            'groupSize' => $this->group_size,
            'languages' => $this->languages,
            'rating' => $this->rating,
            'reviewsCount' => $this->reviews_count,
            'image' => $this->image,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'included' => $this->included,
            'notIncluded' => $this->not_included,
            'itinerary' => $this->itinerary,
            'childrenAllowed' => $this->children_allowed,
            'childrenPrice' => $this->children_price,
            'promotionType' => $this->promotion_type,
            'promotionValue' => $this->promotion_value,
            'promotionActive' => $this->promotion_active,
            'type' => $this->type,
            'availability' => $this->availability,
            'pickupTimes' => $this->pickup_times,
            'priceAdultEur' => $this->price_adult_eur,
            'priceChildEur' => $this->price_child_eur,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'bookingsCount' => $this->whenLoaded('bookings', fn() => $this->bookings->count()),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
