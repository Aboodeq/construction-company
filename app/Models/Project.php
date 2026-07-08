<?php

namespace App\Models;

use App\Models\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, HasSlug, HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'project_category_id',
        'title',
        'slug',
        'client_name',
        'location',
        'area',
        'completion_date',
        'duration',
        'description',
        'cover_image',
        'is_featured',
        'status',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'area' => 'decimal:2',
            'completion_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * The category this project belongs to.
     */
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    /**
     * Services used in this project (many-to-many).
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    /**
     * All images for this project (all types combined).
     */
    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    /**
     * Only gallery-type images.
     */
    public function galleryImages()
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'gallery')->orderBy('order');
    }

    /**
     * Only "before" images.
     */
    public function beforeImages()
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'before')->orderBy('order');
    }

    /**
     * Only "after" images.
     */
    public function afterImages()
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'after')->orderBy('order');
    }

    /**
     * Testimonials related to this project.
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Scope: only published projects.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: only featured projects.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: order by the manual order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
