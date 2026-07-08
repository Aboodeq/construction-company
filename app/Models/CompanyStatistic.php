<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyStatistic extends Model
{
    use HasFactory;
    protected $fillable = [
        'icon',
        'number',
        'suffix',
        'label',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
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
