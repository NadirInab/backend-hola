<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'excursionId' => $this->excursion_id,
            'excursion' => new ExcursionResource($this->whenLoaded('excursion')),
            'userId' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'name' => $this->name,
            'rating' => $this->rating,
            'title' => $this->title,
            'comment' => $this->comment,
            'isApproved' => $this->is_approved,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
