<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\ImageUploader;

class SettingController extends Controller
{
    /**
     * Setting keys that are stored as uploaded files rather than plain values.
     */
    private const FILE_KEYS = ['logo', 'favicon', 'default_og_image'];

    /**
     * Map each non-file setting key to its group, for Setting::set().
     */
    private const GROUPS = [
        'site_name' => 'general',
        'site_tagline' => 'general',
        'maintenance_mode' => 'general',
        'contact_phone' => 'contact',
        'contact_whatsapp' => 'contact',
        'contact_email' => 'contact',
        'contact_address' => 'contact',
        'business_hours' => 'contact',
        'google_maps_embed' => 'contact',
        'facebook_url' => 'social',
        'instagram_url' => 'social',
        'twitter_url' => 'social',
        'linkedin_url' => 'social',
        'youtube_url' => 'social',
        'default_meta_title' => 'seo',
        'default_meta_description' => 'seo',
        'google_analytics_code' => 'seo',
        'primary_color' => 'appearance',
        'accent_color' => 'appearance',
    ];

    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index()
    {
        $this->authorize('settings.edit');

        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->safe()->except(array_merge(
            self::FILE_KEYS,
            ['remove_logo', 'remove_favicon', 'remove_default_og_image'],
        ));

        foreach ($data as $key => $value) {
            Setting::set($key, $value, self::GROUPS[$key] ?? 'general');
        }

        $this->syncFileSetting($request, 'logo', 'remove_logo', 'general');
        $this->syncFileSetting($request, 'favicon', 'remove_favicon', 'general');
        $this->syncFileSetting($request, 'default_og_image', 'remove_default_og_image', 'seo');

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }

    private function syncFileSetting(UpdateSettingsRequest $request, string $key, string $removeKey, string $group): void
    {
        $current = Setting::get($key);

        if ($request->boolean($removeKey) && $current) {
            $this->images->delete($current);
            Setting::set($key, null, $group);

            return;
        }

        if ($request->hasFile($key)) {
            $this->images->delete($current);
            Setting::set($key, $this->images->store($request->file($key), 'settings'), $group);
        }
    }
}
