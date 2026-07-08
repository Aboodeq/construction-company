<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Get a setting value by key, with an optional default.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return Setting::class;
        }

        return Setting::get($key, $default);
    }
}
