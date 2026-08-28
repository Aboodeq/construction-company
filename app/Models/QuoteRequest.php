<?php

namespace App\Models;

use App\Models\Traits\HasEmailReplies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteRequest extends Model
{
    use HasFactory, HasEmailReplies;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'project_type',
        'city',
        'area',
        'estimated_budget',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'area' => 'decimal:2',
        ];
    }

    /**
     * Files uploaded with this quote request.
     */
    public function files()
    {
        return $this->hasMany(QuoteRequestFile::class);
    }

    /**
     * Scope: filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: only new (unread) requests.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Mark this request as read.
     */
    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }
}
