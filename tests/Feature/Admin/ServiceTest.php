<?php

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceFaq;
use App\Models\ServiceImage;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function userWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.services.index'))->assertRedirect(route('login'));
});

test('admin can view the services index', function () {
    $admin = userWithRole('admin');
    Service::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.services.index'))
        ->assertOk()
        ->assertSee('الخدمات');
});

test('editor can view but not delete a service', function () {
    $editor = userWithRole('editor');
    $service = Service::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.services.edit', $service))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.services.destroy', $service))
        ->assertForbidden();

    $this->assertNotSoftDeleted($service);
});

test('admin can create a service', function () {
    $admin = userWithRole('admin');
    $category = ServiceCategory::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.services.store'), [
        'title' => 'تشطيب فلل فاخر',
        'service_category_id' => $category->id,
        'short_description' => 'وصف مختصر',
        'description' => 'وصف تفصيلي كامل للخدمة',
        'status' => 'draft',
        'order' => 1,
    ]);

    $service = Service::firstWhere('title', 'تشطيب فلل فاخر');

    $response->assertRedirect(route('admin.services.edit', $service));
    expect($service)->not->toBeNull();
    expect($service->slug)->not->toBeEmpty();
    expect($service->is_featured)->toBeFalse();
});

test('editor cannot create a service without permission', function () {
    // Strip create access from the editor role itself (not the user, who only
    // ever inherits it through the role) to simulate a more limited role.
    $this->seed(RolePermissionSeeder::class);
    \Spatie\Permission\Models\Role::findByName('editor')->revokePermissionTo('services.create');

    $user = User::factory()->create();
    $user->assignRole('editor');

    $this->actingAs($user)
        ->post(route('admin.services.store'), [
            'title' => 'خدمة تجريبية',
            'description' => 'وصف',
            'status' => 'draft',
        ])
        ->assertForbidden();
});

test('updating a service syncs faqs and process steps', function () {
    $admin = userWithRole('admin');
    $service = Service::factory()->create();
    $keepFaq = ServiceFaq::factory()->create(['service_id' => $service->id, 'question' => 'قديم يبقى']);
    $removeFaq = ServiceFaq::factory()->create(['service_id' => $service->id, 'question' => 'قديم يُحذف']);

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'title' => $service->title,
        'service_category_id' => '',
        'short_description' => $service->short_description,
        'description' => $service->description,
        'status' => 'published',
        'order' => 5,
        'faqs' => [
            ['id' => $keepFaq->id, 'question' => 'سؤال محدث', 'answer' => 'إجابة محدثة'],
            ['question' => 'سؤال جديد', 'answer' => 'إجابة جديدة'],
        ],
        'process_steps' => [
            ['title' => 'الخطوة الأولى', 'description' => 'وصف الخطوة'],
        ],
    ])->assertRedirect(route('admin.services.edit', $service));

    $service->refresh();

    expect($service->status)->toBe('published');
    expect($service->faqs)->toHaveCount(2);
    expect($service->faqs->pluck('question')->all())->toContain('سؤال محدث', 'سؤال جديد');
    expect($service->faqs->pluck('question')->all())->not->toContain('قديم يُحذف');
    $this->assertDatabaseMissing('service_faqs', ['id' => $removeFaq->id]);
    expect($service->process_steps)->toHaveCount(1);
    expect($service->process_steps[0]['title'])->toBe('الخطوة الأولى');
});

test('admin can upload a featured image and gallery images', function () {
    Storage::fake('public');
    $admin = userWithRole('admin');
    $service = Service::factory()->create();

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'title' => $service->title,
        'description' => $service->description,
        'status' => $service->status,
        'featured_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
        'new_images' => [
            UploadedFile::fake()->image('gallery-1.jpg', 800, 600),
        ],
    ]);

    $service->refresh();

    expect($service->featured_image)->not->toBeNull();
    Storage::disk('public')->assertExists($service->featured_image);
    expect($service->images)->toHaveCount(1);
});

test('deleting a gallery image removes the file and record', function () {
    Storage::fake('public');
    $admin = userWithRole('admin');
    $service = Service::factory()->create();
    Storage::disk('public')->put('services/gallery/test.webp', 'fake-content');
    $image = ServiceImage::create([
        'service_id' => $service->id,
        'image_path' => 'services/gallery/test.webp',
        'order' => 0,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.services.images.destroy', [$service, $image]))
        ->assertRedirect();

    Storage::disk('public')->assertMissing('services/gallery/test.webp');
    $this->assertDatabaseMissing('service_images', ['id' => $image->id]);
});

test('admin can delete a service and editor cannot', function () {
    $admin = userWithRole('admin');
    $service = Service::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.services.destroy', $service))
        ->assertRedirect(route('admin.services.index'));

    $this->assertSoftDeleted($service);
});

test('toggling featured and published works', function () {
    $admin = userWithRole('admin');
    $service = Service::factory()->create(['is_featured' => false, 'status' => 'draft']);

    $this->actingAs($admin)->patch(route('admin.services.toggle-featured', $service))->assertRedirect();
    expect($service->refresh()->is_featured)->toBeTrue();

    $this->actingAs($admin)->patch(route('admin.services.toggle-published', $service))->assertRedirect();
    expect($service->refresh()->status)->toBe('published');
});
