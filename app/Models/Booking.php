<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'provider_id',
        'description',
        'address',
        'preferred_date',
        'preferred_time',
        'quoted_price',
        'status',
        'provider_notes',
        'responded_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'preferred_time' => 'datetime:H:i',
        'quoted_price' => 'decimal:2',
        'responded_at' => 'datetime',
    ];

    // İlişkiler
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scope'lar
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Yardımcı metodlar
    public function accept($providerNotes = null)
    {
        $this->update([
            'status' => 'accepted',
            'provider_notes' => $providerNotes,
            'responded_at' => now(),
        ]);
    }

    public function reject($providerNotes = null)
    {
        $this->update([
            'status' => 'rejected',
            'provider_notes' => $providerNotes,
            'responded_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
