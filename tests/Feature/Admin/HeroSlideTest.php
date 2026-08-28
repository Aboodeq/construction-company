<?php

use App\Models\HeroSlide;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function heroSlideUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.hero-slides.index'))->assertRedirect(route('login'));
});

test('admin can view the hero slides index', function () {
    $admin = heroSlideUserWithRole('admin');
    HeroSlide::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.hero-slides.index'))
        ->assertOk()
        ->assertSee('شرائح البانر');
});

test('editor without homepage.edit permission is forbidden', function () {
    $editor = heroSlideUserWithRole('editor');

    $this->actingAs($editor)
        ->get(route('admin.hero-slides.index'))
        ->assertForbidden();
});

test('admin can create a hero slide', function () {
    $admin = heroSlideUserWithRole('admin');

    $this->actingAs($admin)->post(route('admin.hero-slides.store'), [
        'title' => 'شريحة تجريبية',
        'subtitle' => 'وصف الشريحة',
        'status' => 'published',
        'order' => 1,
    ])->assertRedirect();

    $this->assertDatabaseHas('hero_slides', ['title' => 'شريحة تجريبية']);
});

test('admin can upload a hero slide image', function () {
    Storage::fake('public');
    $admin = heroSlideUserWithRole('admin');
    $heroSlide = HeroSlide::factory()->create();

    $this->actingAs($admin)->put(route('admin.hero-slides.update', $heroSlide), [
        'title' => $heroSlide->title,
        'status' => $heroSlide->status,
        'image' => UploadedFile::fake()->image('slide.jpg', 1600, 800),
    ]);

    $heroSlide->refresh();
    expect($heroSlide->image)->not->toBeNull();
    Storage::disk('public')->assertExists($heroSlide->image);
});

test('admin can toggle a hero slide published status', function () {
    $admin = heroSlideUserWithRole('admin');
    $heroSlide = HeroSlide::factory()->create(['status' => 'published']);

    $this->actingAs($admin)->patch(route('admin.hero-slides.toggle-published', $heroSlide))->assertRedirect();
    expect($heroSlide->refresh()->status)->toBe('draft');
});

test('admin can delete a hero slide', function () {
    $admin = heroSlideUserWithRole('admin');
    $heroSlide = HeroSlide::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.hero-slides.destroy', $heroSlide))
        ->assertRedirect(route('admin.hero-slides.index'));

    $this->assertModelMissing($heroSlide);
});
