<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalculatorSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_type_id',
        'finishing_level_id',
        'area',
        'estimated_cost',
        'name',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'area' => 'decimal:2',
            'estimated_cost' => 'decimal:2',
        ];
    }

    /**
     * The property type used in this submission.
     */
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * The finishing level used in this submission.
     */
    public function finishingLevel()
    {
        return $this->belongsTo(FinishingLevel::class);
    }
}
