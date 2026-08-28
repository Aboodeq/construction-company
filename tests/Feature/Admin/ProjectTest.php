<?php

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function projectUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
});

test('admin can view the projects index', function () {
    $admin = projectUserWithRole('admin');
    Project::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.projects.index'))
        ->assertOk()
        ->assertSee('المشاريع');
});

test('editor can view but not delete a project', function () {
    $editor = projectUserWithRole('editor');
    $project = Project::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.projects.edit', $project))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.projects.destroy', $project))
        ->assertForbidden();

    $this->assertNotSoftDeleted($project);
});

test('admin can create a project', function () {
    $admin = projectUserWithRole('admin');
    $category = ProjectCategory::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
        'title' => 'فيلا العائلة الملكية',
        'project_category_id' => $category->id,
        'client_name' => 'أحمد الشمري',
        'location' => 'الرياض',
        'area' => 450,
        'duration' => '4 أشهر',
        'description' => 'وصف تفصيلي للمشروع',
        'status' => 'draft',
        'order' => 1,
    ]);

    $project = Project::firstWhere('title', 'فيلا العائلة الملكية');

    $response->assertRedirect(route('admin.projects.edit', $project));
    expect($project)->not->toBeNull();
    expect($project->slug)->not->toBeEmpty();
    expect($project->is_featured)->toBeFalse();
});

test('updating a project syncs linked services and typed images', function () {
    Storage::fake('public');
    $admin = projectUserWithRole('admin');
    $project = Project::factory()->create();
    $services = Service::factory()->count(2)->create();

    $this->actingAs($admin)->put(route('admin.projects.update', $project), [
        'title' => $project->title,
        'description' => $project->description,
        'status' => 'published',
        'services' => $services->pluck('id')->all(),
        'before_images' => [UploadedFile::fake()->image('before.jpg', 800, 600)],
        'after_images' => [UploadedFile::fake()->image('after.jpg', 800, 600)],
    ])->assertRedirect(route('admin.projects.edit', $project));

    $project->refresh();

    expect($project->status)->toBe('published');
    expect($project->services)->toHaveCount(2);
    expect($project->beforeImages)->toHaveCount(1);
    expect($project->afterImages)->toHaveCount(1);
});

test('admin can upload a cover image', function () {
    Storage::fake('public');
    $admin = projectUserWithRole('admin');
    $project = Project::factory()->create();

    $this->actingAs($admin)->put(route('admin.projects.update', $project), [
        'title' => $project->title,
        'description' => $project->description,
        'status' => $project->status,
        'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
    ]);

    $project->refresh();

    expect($project->cover_image)->not->toBeNull();
    Storage::disk('public')->assertExists($project->cover_image);
});

test('deleting a project image removes the file and record', function () {
    Storage::fake('public');
    $admin = projectUserWithRole('admin');
    $project = Project::factory()->create();
    Storage::disk('public')->put('projects/gallery/test.webp', 'fake-content');
    $image = ProjectImage::create([
        'project_id' => $project->id,
        'image_path' => 'projects/gallery/test.webp',
        'type' => 'gallery',
        'order' => 0,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.projects.images.destroy', [$project, $image]))
        ->assertRedirect();

    Storage::disk('public')->assertMissing('projects/gallery/test.webp');
    $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
});

test('admin can delete a project and editor cannot', function () {
    $admin = projectUserWithRole('admin');
    $project = Project::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.projects.destroy', $project))
        ->assertRedirect(route('admin.projects.index'));

    $this->assertSoftDeleted($project);
});

test('toggling featured and published works', function () {
    $admin = projectUserWithRole('admin');
    $project = Project::factory()->create(['is_featured' => false, 'status' => 'draft']);

    $this->actingAs($admin)->patch(route('admin.projects.toggle-featured', $project))->assertRedirect();
    expect($project->refresh()->is_featured)->toBeTrue();

    $this->actingAs($admin)->patch(route('admin.projects.toggle-published', $project))->assertRedirect();
    expect($project->refresh()->status)->toBe('published');
});
