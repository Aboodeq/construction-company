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
        return Cache::rememberForever("page_section_{$key}", function () use ($key) {
            return static::where('key', $key)->first();
        });
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
