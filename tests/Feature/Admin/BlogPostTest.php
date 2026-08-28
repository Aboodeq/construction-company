<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Storage;

function blogUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.blog.index'))->assertRedirect(route('login'));
});

test('admin can view the blog index', function () {
    $admin = blogUserWithRole('admin');
    BlogPost::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.blog.index'))
        ->assertOk()
        ->assertSee('المدونة');
});

test('editor can view but not delete a post', function () {
    $editor = blogUserWithRole('editor');
    $post = BlogPost::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.blog.edit', $post))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.blog.destroy', $post))
        ->assertForbidden();

    $this->assertNotSoftDeleted($post);
});

test('creating a published post without a date sets published_at automatically', function () {
    $admin = blogUserWithRole('admin');
    $category = BlogCategory::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.blog.store'), [
        'title' => 'أهم اتجاهات التشطيبات',
        'blog_category_id' => $category->id,
        'excerpt' => 'مقتطف قصير',
        'content' => 'نص المقالة الكامل هنا.',
        'status' => 'published',
    ]);

    $post = BlogPost::firstWhere('title', 'أهم اتجاهات التشطيبات');

    $response->assertRedirect(route('admin.blog.edit', $post));
    expect($post->author_id)->toBe($admin->id);
    expect($post->published_at)->not->toBeNull();
});

test('creating a draft post leaves published_at empty', function () {
    $admin = blogUserWithRole('admin');

    $this->actingAs($admin)->post(route('admin.blog.store'), [
        'title' => 'مسودة تجريبية',
        'content' => 'نص المقالة.',
        'status' => 'draft',
    ]);

    $post = BlogPost::firstWhere('title', 'مسودة تجريبية');
    expect($post->published_at)->toBeNull();
});

test('admin can upload a featured image', function () {
    Storage::fake('public');
    $admin = blogUserWithRole('admin');
    $post = BlogPost::factory()->create();

    $this->actingAs($admin)->put(route('admin.blog.update', $post), [
        'title' => $post->title,
        'content' => $post->content,
        'status' => $post->status,
        'featured_image' => \Illuminate\Http\UploadedFile::fake()->image('cover.jpg', 800, 600),
    ]);

    $post->refresh();
    expect($post->featured_image)->not->toBeNull();
    Storage::disk('public')->assertExists($post->featured_image);
});

test('toggling published status sets published_at the first time only', function () {
    $admin = blogUserWithRole('admin');
    $post = BlogPost::factory()->create(['status' => 'draft', 'published_at' => null]);

    $this->actingAs($admin)->patch(route('admin.blog.toggle-published', $post))->assertRedirect();
    $post->refresh();
    expect($post->status)->toBe('published');
    expect($post->published_at)->not->toBeNull();

    $firstPublishedAt = $post->published_at;

    $this->actingAs($admin)->patch(route('admin.blog.toggle-published', $post))->assertRedirect();
    $post->refresh();
    expect($post->status)->toBe('draft');

    $this->actingAs($admin)->patch(route('admin.blog.toggle-published', $post))->assertRedirect();
    $post->refresh();
    expect($post->status)->toBe('published');
    expect($post->published_at->equalTo($firstPublishedAt))->toBeTrue();
});

test('admin can delete a post', function () {
    $admin = blogUserWithRole('admin');
    $post = BlogPost::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.blog.destroy', $post))
        ->assertRedirect(route('admin.blog.index'));

    $this->assertSoftDeleted($post);
});
