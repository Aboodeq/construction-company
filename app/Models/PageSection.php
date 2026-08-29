<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'image',
        'extra_data',
    ];

    protected function casts(): array
    {
        return [
            'extra_data' => 'array',
        ];
    }

    /**
     * Get a page section by its unique key, with caching.
     */
    public static function getByKey(string $key): ?self
    {
        // Cache the raw attributes array, not the model itself: the app disables
        // unserializing arbitrary objects from the cache (config/cache.php
        // `serializable_classes`), which would otherwise silently hydrate this
        // back as a useless __PHP_Incomplete_Class on every cache hit.
        $attributes = Cache::rememberForever("page_section_{$key}", function () use ($key) {
            return static::where('key', $key)->first()?->getAttributes();
        });

        return $attributes ? (new static)->newFromBuilder($attributes) : null;
    }

    /**
     * Clear the cache for this specific section.
     */
    protected static function booted(): void
    {
        static::saved(function (self $section) {
            Cache::forget("page_section_{$section->key}");
        });

        static::deleted(function (self $section) {
            Cache::forget("page_section_{$section->key}");
        });
    }
}
