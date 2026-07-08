<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessStep extends Model
{
    use HasFactory;
    protected $fillable = [
        'step_number',
        'icon',
        'title',
        'description',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'step_number' => 'integer',
        ];
    }

    /**
     * Scope: order by the manual order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
