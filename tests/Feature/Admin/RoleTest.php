<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

function roleAdminActor(): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

test('editor cannot view the roles index', function () {
    test()->seed(RolePermissionSeeder::class);
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    test()->actingAs($editor)->get(route('admin.roles.index'))->assertForbidden();
});

test('admin can create a custom role with a subset of permissions', function () {
    $admin = roleAdminActor();

    $this->actingAs($admin)->post(route('admin.roles.store'), [
        'name' => 'blog-writer',
        'permissions' => ['blog.view', 'blog.create', 'blog.edit'],
    ])->assertRedirect(route('admin.roles.index'));

    $role = Role::findByName('blog-writer');
    expect($role)->not->toBeNull();
    expect($role->permissions->pluck('name')->sort()->values()->all())
        ->toBe(['blog.create', 'blog.edit', 'blog.view']);
});

test('a user assigned a custom role can enter the panel and only use its permissions', function () {
    $admin = roleAdminActor();
    $role = Role::create(['name' => 'blog-writer']);
    $role->givePermissionTo(['blog.view', 'blog.create', 'blog.edit']);

    $writer = User::factory()->create();
    $writer->assignRole('blog-writer');

    // Can enter the panel at all (previously hardcoded to only admin/editor).
    $this->actingAs($writer)->get(route('admin.dashboard'))->assertOk();

    // Has no services permissions, so services.* stays forbidden.
    $this->actingAs($writer)->get(route('admin.services.index'))->assertForbidden();
});

test('the admin role cannot be edited or deleted', function () {
    $admin = roleAdminActor();
    $adminRole = Role::findByName('admin');

    $this->actingAs($admin)
        ->put(route('admin.roles.update', $adminRole), ['name' => 'admin', 'permissions' => []])
        ->assertRedirect();
    expect($adminRole->refresh()->permissions()->count())->toBeGreaterThan(0);

    $this->actingAs($admin)
        ->delete(route('admin.roles.destroy', $adminRole))
        ->assertRedirect();
    $this->assertModelExists($adminRole);
});

test('a role with users attached cannot be deleted', function () {
    $admin = roleAdminActor();
    $role = Role::create(['name' => 'blog-writer']);
    $writer = User::factory()->create();
    $writer->assignRole('blog-writer');

    $this->actingAs($admin)
        ->delete(route('admin.roles.destroy', $role))
        ->assertRedirect();

    $this->assertModelExists($role);
});

test('a role with no users attached can be deleted', function () {
    $admin = roleAdminActor();
    $role = Role::create(['name' => 'unused-role']);

    $this->actingAs($admin)
        ->delete(route('admin.roles.destroy', $role))
        ->assertRedirect(route('admin.roles.index'));

    $this->assertModelMissing($role);
});
