<?php

use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function pageSectionUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.page-sections.index'))->assertRedirect(route('login'));
});

test('admin can view the page sections index', function () {
    $admin = pageSectionUserWithRole('admin');
    PageSection::create(['key' => 'about_story', 'title' => 'قصتنا']);

    $this->actingAs($admin)
        ->get(route('admin.page-sections.index'))
        ->assertOk()
        ->assertSee('أقسام الصفحات');
});

test('editor without homepage.edit permission is forbidden', function () {
    $editor = pageSectionUserWithRole('editor');
    $section = PageSection::create(['key' => 'about_story', 'title' => 'قصتنا']);

    $this->actingAs($editor)
        ->get(route('admin.page-sections.edit', $section))
        ->assertForbidden();
});

test('admin can update a page section', function () {
    $admin = pageSectionUserWithRole('admin');
    $section = PageSection::create(['key' => 'about_story', 'title' => 'قصتنا']);

    $this->actingAs($admin)->put(route('admin.page-sections.update', $section), [
        'title' => 'قصتنا المحدّثة',
        'content' => 'نص جديد',
    ])->assertRedirect();

    expect($section->refresh()->title)->toBe('قصتنا المحدّثة');
});

test('admin can update why_choose_us points', function () {
    $admin = pageSectionUserWithRole('admin');
    $section = PageSection::create(['key' => 'why_choose_us', 'title' => 'لماذا تختارنا']);

    $this->actingAs($admin)->put(route('admin.page-sections.update', $section), [
        'title' => $section->title,
        'points' => [
            ['icon' => 'shield-check', 'title' => 'ضمان الجودة', 'description' => 'وصف'],
        ],
    ])->assertRedirect();

    expect($section->refresh()->extra_data['points'])->toHaveCount(1)
        ->and($section->extra_data['points'][0]['title'])->toBe('ضمان الجودة');
});
