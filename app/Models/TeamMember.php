<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'position',
        'image',
        'bio',
        'social_links',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    /**
     * Scope: only published team members.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: order by the manual order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
