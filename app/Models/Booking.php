<?php

namespace App\Models;

use App\Models\Traits\HasEmailReplies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, HasEmailReplies;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'preferred_date',
        'preferred_time',
        'city',
        'address',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    /**
     * Scope: filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: only pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
