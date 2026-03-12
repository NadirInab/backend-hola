<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'duration',
        'category_id',
        'destination_id',
        'group_size',
        'languages',
        'rating',
        'reviews_count',
        'image',
        'latitude',
        'longitude',
        'included',
        'not_included',
        'itinerary',
        'children_allowed',
        'children_price',
        'promotion_type',
        'promotion_value',
        'promotion_active',
        'type',
        'availability',
        'pickup_times',
        'price_adult_eur',
        'price_child_eur',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'reviews_count' => 'integer',
        'included' => 'array',
        'not_included' => 'array',
        'itinerary' => 'array',
        'children_allowed' => 'boolean',
        'children_price' => 'float',
        'promotion_value' => 'array',
        'promotion_active' => 'boolean',
        'availability' => 'array',
        'pickup_times' => 'array',
        'price_adult_eur' => 'float',
        'price_child_eur' => 'float',
        'availability' => 'array',
    'pickup_times' => 'array',
    ];

    /**
     * Get the category that owns the activity.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the destination that owns the activity.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the bookings for the activity.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'bookable_id')->where('bookable_type', self::class);
    }

    /**
     * Get the reviews for the activity.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewable_id')->where('reviewable_type', self::class);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to filter by price range
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope to filter by rating
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope to filter by location (search in title and description)
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where(function ($q) use ($location) {
            $q->where('title', 'like', "%{$location}%")
              ->orWhere('description', 'like', "%{$location}%");
        });
    }

    /**
     * Scope to filter by minimum group size
     */
    public function scopeMinGroupSize($query, $size)
    {
        return $query->where('group_size', '>=', $size);
    }
}
