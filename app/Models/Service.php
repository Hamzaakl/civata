<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_category_id',
        'title',
        'description',
        'price_min',
        'price_max',
        'price_type',
        'service_area',
        'images',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // İlişkiler
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('service_category_id', $categoryId);
    }

    public function scopeByCity($query, $city)
    {
        return $query->whereHas('user', function ($q) use ($city) {
            $q->where('city', $city);
        });
    }

    // Yardımcı metodlar
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getPriceRangeAttribute()
    {
        if ($this->price_type === 'fixed' && $this->price_min) {
            return number_format($this->price_min, 2) . ' TL';
        } elseif ($this->price_min && $this->price_max) {
            return number_format($this->price_min, 2) . ' - ' . number_format($this->price_max, 2) . ' TL';
        } elseif ($this->price_min) {
            return number_format($this->price_min, 2) . ' TL\'den başlayan fiyatlar';
        }
        return 'Fiyat görüş';
    }
}
