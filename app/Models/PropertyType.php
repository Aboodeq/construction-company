<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'base_price_per_meter',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'base_price_per_meter' => 'decimal:2',
        ];
    }

    /**
     * Submissions that used this property type.
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
