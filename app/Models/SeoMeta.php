<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
    ];

    /**
     * Get the owning seo_metable model (Service, Project, BlogPost, etc.)
     */
    public function seoMetable(): MorphTo
    {
        return $this->morphTo();
    }
}
