<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinishingLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'multiplier',
        'description',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'multiplier' => 'decimal:2',
        ];
    }

    /**
     * Submissions that used this finishing level.
     */
    public function submissions()
    {
        return $this->hasMany(CalculatorSubmission::class);
    }

    /**
     * Scope: order by the manual order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
