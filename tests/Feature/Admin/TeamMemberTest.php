<?php

use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function teamUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.team.index'))->assertRedirect(route('login'));
});

test('admin can view the team index', function () {
    $admin = teamUserWithRole('admin');
    TeamMember::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.team.index'))
        ->assertOk()
        ->assertSee('فريق العمل');
});

test('editor can view but not delete a team member', function () {
    $editor = teamUserWithRole('editor');
    $member = TeamMember::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.team.edit', $member))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.team.destroy', $member))
        ->assertForbidden();

    $this->assertModelExists($member);
});

test('admin can create a team member with social links, empty ones are dropped', function () {
    $admin = teamUserWithRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => 'Test Member',
        'position' => 'Test Position',
        'status' => 'published',
        'social_links' => [
            'linkedin' => 'https://linkedin.com/in/test',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ],
    ]);

    $member = TeamMember::firstWhere('name', 'Test Member');
    $response->assertRedirect(route('admin.team.edit', $member));
    expect($member->social_links)->toBe(['linkedin' => 'https://linkedin.com/in/test']);
});

test('admin can upload a photo', function () {
    Storage::fake('public');
    $admin = teamUserWithRole('admin');
    $member = TeamMember::factory()->create();

    $this->actingAs($admin)->put(route('admin.team.update', $member), [
        'name' => $member->name,
        'position' => $member->position,
        'status' => $member->status,
        'image' => UploadedFile::fake()->image('member.jpg', 400, 400),
    ]);

    $member->refresh();
    expect($member->image)->not->toBeNull();
    Storage::disk('public')->assertExists($member->image);
});

test('deleting a team member also removes their photo file', function () {
    Storage::fake('public');
    $admin = teamUserWithRole('admin');
    Storage::disk('public')->put('team/test.webp', 'fake-content');
    $member = TeamMember::factory()->create(['image' => 'team/test.webp']);

    $this->actingAs($admin)
        ->delete(route('admin.team.destroy', $member))
        ->assertRedirect(route('admin.team.index'));

    $this->assertModelMissing($member);
    Storage::disk('public')->assertMissing('team/test.webp');
});

test('toggling published status works', function () {
    $admin = teamUserWithRole('admin');
    $member = TeamMember::factory()->create(['status' => 'draft']);

    $this->actingAs($admin)->patch(route('admin.team.toggle-published', $member))->assertRedirect();
    expect($member->refresh()->status)->toBe('published');
});
