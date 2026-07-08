<?php

namespace App\Models;

use App\Models\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, HasSlug, HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'service_category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'icon',
        'featured_image',
        'process_steps',
        'is_featured',
        'status',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'process_steps' => 'array',
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
     * The category this service belongs to.
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Gallery images for this service.
     */
    public function images()
    {
        return $this->hasMany(ServiceImage::class)->orderBy('order');
    }

    /**
     * FAQs for this service.
     */
    public function faqs()
    {
        return $this->hasMany(ServiceFaq::class)->orderBy('order');
    }

    /**
     * Projects that used this service (many-to-many).
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_service');
    }

    /**
     * Scope: only published services.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: only featured services.
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
