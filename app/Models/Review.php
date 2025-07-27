<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
        'images',
        'is_approved',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
    ];

    // İlişkiler
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    // Scope'lar
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Model eventi
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->updateUserRating();
        });

        static::updated(function ($review) {
            $review->updateUserRating();
        });

        static::deleted(function ($review) {
            $review->updateUserRating();
        });
    }

    // Kullanıcının ortalama puanını güncelle
    private function updateUserRating()
    {
        $user = $this->reviewee;
        $avgRating = $user->receivedReviews()->approved()->avg('rating');
        $totalReviews = $user->receivedReviews()->approved()->count();

        $user->update([
            'rating' => $avgRating ?? 0,
            'total_reviews' => $totalReviews,
        ]);
    }
}
