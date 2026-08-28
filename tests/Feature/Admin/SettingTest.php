<?php

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function settingUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.settings.index'))->assertRedirect(route('login'));
});

test('admin can view the settings page', function () {
    $admin = settingUserWithRole('admin');
    Setting::set('site_name', 'شركة تجريبية');

    $this->actingAs($admin)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertSee('إعدادات الموقع');
});

test('editor without settings.edit permission is forbidden', function () {
    $editor = settingUserWithRole('editor');

    $this->actingAs($editor)
        ->get(route('admin.settings.index'))
        ->assertForbidden();
});

test('admin can update settings across groups', function () {
    $admin = settingUserWithRole('admin');

    $this->actingAs($admin)->put(route('admin.settings.update'), [
        'site_name' => 'اسم جديد',
        'site_tagline' => 'شعار جديد',
        'contact_phone' => '0500000000',
        'contact_email' => 'info@example.test',
        'facebook_url' => 'https://facebook.com/example',
        'default_meta_title' => 'عنوان SEO',
        'primary_color' => '#111111',
        'accent_color' => '#a9812e',
    ])->assertRedirect();

    expect(Setting::get('site_name'))->toBe('اسم جديد')
        ->and(Setting::get('contact_email'))->toBe('info@example.test')
        ->and(Setting::get('facebook_url'))->toBe('https://facebook.com/example')
        ->and(Setting::get('primary_color'))->toBe('#111111');
});

test('admin can upload a logo', function () {
    Storage::fake('public');
    $admin = settingUserWithRole('admin');

    $this->actingAs($admin)->put(route('admin.settings.update'), [
        'site_name' => 'اسم الموقع',
        'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
    ]);

    $logoPath = Setting::get('logo');
    expect($logoPath)->not->toBeNull();
    Storage::disk('public')->assertExists($logoPath);
});

test('maintenance mode checkbox correctly saves off when unchecked', function () {
    $admin = settingUserWithRole('admin');
    Setting::set('maintenance_mode', '1', 'general');

    $this->actingAs($admin)->put(route('admin.settings.update'), [
        'site_name' => 'اسم الموقع',
        'maintenance_mode' => '0',
    ]);

    expect(Setting::get('maintenance_mode'))->toBe('0');
});
