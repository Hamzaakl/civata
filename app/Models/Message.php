<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'booking_id',
        'message',
        'attachments',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // İlişkiler
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scope'lar
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId1)
                     ->where('recipient_id', $userId2);
            })->orWhere(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId2)
                     ->where('recipient_id', $userId1);
            });
        });
    }

    // Yardımcı metodlar
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function isFromUser($userId)
    {
        return $this->sender_id == $userId;
    }

    public function isToUser($userId)
    {
        return $this->recipient_id == $userId;
    }
}
