<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('settings.edit');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // عام
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'remove_favicon' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],

            // التواصل
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'business_hours' => ['nullable', 'string', 'max:255'],
            'google_maps_embed' => ['nullable', 'string'],

            // التواصل الاجتماعي
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],

            // SEO
            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string', 'max:500'],
            'default_og_image' => ['nullable', 'image', 'max:2048'],
            'remove_default_og_image' => ['nullable', 'boolean'],
            'google_analytics_code' => ['nullable', 'string', 'max:255'],

            // المظهر
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }
}
