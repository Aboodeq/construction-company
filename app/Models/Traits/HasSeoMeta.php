<?php

namespace App\Models\Traits;

use App\Models\SeoMeta;

trait HasSeoMeta
{
    /**
     * Get the SEO meta data associated with this model.
     */
    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'seo_metable');
    }

    /**
     * Get the meta title, falling back to the model's own title/name if not set.
     */
    public function getMetaTitleAttribute(): string
    {
        return $this->seoMeta?->meta_title
            ?? $this->title
            ?? $this->name
            ?? config('app.name');
    }

    /**
     * Get the meta description, falling back to a truncated short description if not set.
     */
    public function getMetaDescriptionAttribute(): ?string
    {
        if ($this->seoMeta?->meta_description) {
            return $this->seoMeta->meta_description;
        }

        $fallback = $this->short_description ?? $this->excerpt ?? strip_tags($this->description ?? '');

        return $fallback ? str($fallback)->limit(160) : null;
    }

    /**
     * Get the Open Graph image, falling back to the model's featured/cover image.
     */
    public function getOgImageAttribute(): ?string
    {
        return $this->seoMeta?->og_image
            ?? $this->featured_image
            ?? $this->cover_image
            ?? null;
    }
}
