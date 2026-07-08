<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'image_path',
        'type',
        'order',
    ];

    /**
     * The project this image belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope: filter by image type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
