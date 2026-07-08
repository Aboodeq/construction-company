<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'شركة البناء الفاخر', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'التميز في كل تفصيلة', 'group' => 'general'],
            ['key' => 'logo', 'value' => null, 'group' => 'general'],
            ['key' => 'favicon', 'value' => null, 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general'],

            // Contact
            ['key' => 'contact_phone', 'value' => '+966500000000', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '+966500000000', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@construction-company.test', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'الرياض، المملكة العربية السعودية', 'group' => 'contact'],
            ['key' => 'business_hours', 'value' => 'السبت - الخميس: 9 صباحًا - 6 مساءً', 'group' => 'contact'],
            ['key' => 'google_maps_embed', 'value' => null, 'group' => 'contact'],

            // Social
            ['key' => 'facebook_url', 'value' => null, 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => null, 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => null, 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => null, 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => null, 'group' => 'social'],

            // SEO Defaults
            ['key' => 'default_meta_title', 'value' => 'شركة البناء الفاخر | تشطيبات ومقاولات', 'group' => 'seo'],
            ['key' => 'default_meta_description', 'value' => 'شركة متخصصة في التشطيبات الفاخرة والمقاولات العامة للفلل والشقق والمشاريع التجارية.', 'group' => 'seo'],
            ['key' => 'default_og_image', 'value' => null, 'group' => 'seo'],
            ['key' => 'google_analytics_code', 'value' => null, 'group' => 'seo'],

            // Appearance
            ['key' => 'primary_color', 'value' => '#0a0a0a', 'group' => 'appearance'],
            ['key' => 'accent_color', 'value' => '#c9a227', 'group' => 'appearance'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
