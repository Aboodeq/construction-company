<?php

use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function testimonialUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.testimonials.index'))->assertRedirect(route('login'));
});

test('admin can view the testimonials index', function () {
    $admin = testimonialUserWithRole('admin');
    Testimonial::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.testimonials.index'))
        ->assertOk()
        ->assertSee('آراء العملاء');
});

test('editor can view but not delete a testimonial', function () {
    $editor = testimonialUserWithRole('editor');
    $testimonial = Testimonial::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.testimonials.edit', $testimonial))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.testimonials.destroy', $testimonial))
        ->assertForbidden();

    $this->assertModelExists($testimonial);
});

test('admin can create a testimonial linked to a project', function () {
    $admin = testimonialUserWithRole('admin');
    $project = Project::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.testimonials.store'), [
        'project_id' => $project->id,
        'client_name' => 'Test Client',
        'rating' => 5,
        'review' => 'Excellent work from start to finish.',
        'status' => 'published',
        'order' => 0,
    ]);

    $testimonial = Testimonial::firstWhere('client_name', 'Test Client');
    $response->assertRedirect(route('admin.testimonials.edit', $testimonial));
    expect($testimonial->project_id)->toBe($project->id);
    expect($testimonial->rating)->toBe(5);
});

test('rating is validated between 1 and 5', function () {
    $admin = testimonialUserWithRole('admin');

    $this->actingAs($admin)->post(route('admin.testimonials.store'), [
        'client_name' => 'Test Client',
        'rating' => 9,
        'review' => 'Some review text.',
        'status' => 'published',
    ])->assertSessionHasErrors('rating');
});

test('admin can upload a client image', function () {
    Storage::fake('public');
    $admin = testimonialUserWithRole('admin');
    $testimonial = Testimonial::factory()->create();

    $this->actingAs($admin)->put(route('admin.testimonials.update', $testimonial), [
        'client_name' => $testimonial->client_name,
        'rating' => $testimonial->rating,
        'review' => $testimonial->review,
        'status' => $testimonial->status,
        'client_image' => UploadedFile::fake()->image('client.jpg', 400, 400),
    ]);

    $testimonial->refresh();
    expect($testimonial->client_image)->not->toBeNull();
    Storage::disk('public')->assertExists($testimonial->client_image);
});

test('deleting a testimonial also removes its image file', function () {
    Storage::fake('public');
    $admin = testimonialUserWithRole('admin');
    Storage::disk('public')->put('testimonials/test.webp', 'fake-content');
    $testimonial = Testimonial::factory()->create(['client_image' => 'testimonials/test.webp']);

    $this->actingAs($admin)
        ->delete(route('admin.testimonials.destroy', $testimonial))
        ->assertRedirect(route('admin.testimonials.index'));

    $this->assertModelMissing($testimonial);
    Storage::disk('public')->assertMissing('testimonials/test.webp');
});

test('toggling featured and published works', function () {
    $admin = testimonialUserWithRole('admin');
    $testimonial = Testimonial::factory()->create(['is_featured' => false, 'status' => 'pending']);

    $this->actingAs($admin)->patch(route('admin.testimonials.toggle-featured', $testimonial))->assertRedirect();
    expect($testimonial->refresh()->is_featured)->toBeTrue();

    $this->actingAs($admin)->patch(route('admin.testimonials.toggle-published', $testimonial))->assertRedirect();
    expect($testimonial->refresh()->status)->toBe('published');
});
