<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_token',
        'visitor_name',
        'visitor_email',
        'status',
        'assigned_to',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Number of visitor messages the admin side hasn't read yet.
     */
    public function scopeWithUnreadCount($query)
    {
        return $query->withCount(['messages as unread_count' => function ($query) {
            $query->where('sender_type', 'visitor')->whereNull('read_at');
        }]);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
