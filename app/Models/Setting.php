<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by its key, with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('all_settings', function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set (create or update) a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    /**
     * Get all settings belonging to a specific group.
     */
    public static function group(string $group)
    {
        return static::where('group', $group)->get();
    }

    /**
     * Clear the settings cache whenever any setting is saved or deleted.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('all_settings');
        });

        static::deleted(function () {
            Cache::forget('all_settings');
        });
    }
}
