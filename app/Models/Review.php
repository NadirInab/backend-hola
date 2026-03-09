<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'excursion_id',
        'reviewable_id',
        'reviewable_type',
        'user_id',
        'name',
        'rating',
        'title',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    const MIN_RATING = 1;
    const MAX_RATING = 5;

    /**
     * Get the reviewable model (Excursion, Circuit, or Activity).
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the excursion associated with the review (for backwards compatibility).
     */
    public function excursion(): BelongsTo
    {
        return $this->belongsTo(Excursion::class);
    }

    /**
     * Get the user associated with the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get high-rated reviews
     */
    public function scopeHighRated($query, $rating = 4)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope to get reviews for a specific excursion
     */
    public function scopeForExcursion($query, $excursionId)
    {
        return $query->where('excursion_id', $excursionId);
    }

    /**
     * Scope to get recent reviews
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}

