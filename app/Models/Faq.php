<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;
    protected $fillable = [
        'category',
        'question',
        'answer',
        'order',
        'status',
    ];

    /**
     * Scope: only published FAQs.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: filter by category.
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: order by the manual order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
