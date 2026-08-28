<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function adminActor(): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});

test('editor cannot view the users index', function () {
    $this->seed(RolePermissionSeeder::class);
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
});

test('a user without any role is blocked from the whole admin panel', function () {
    $this->seed(RolePermissionSeeder::class);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin can create a user and assign a role', function () {
    $admin = adminActor();

    $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'محرر جديد',
        'email' => 'new-editor@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'editor',
        'is_active' => '1',
    ])->assertRedirect(route('admin.users.index'));

    $created = User::firstWhere('email', 'new-editor@example.com');
    expect($created)->not->toBeNull();
    expect($created->hasRole('editor'))->toBeTrue();
});

test('admin can update a user and change their role', function () {
    $admin = adminActor();
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    $this->actingAs($admin)->put(route('admin.users.update', $editor), [
        'name' => $editor->name,
        'email' => $editor->email,
        'role' => 'admin',
        'is_active' => '1',
    ])->assertRedirect(route('admin.users.index'));

    expect($editor->refresh()->hasRole('admin'))->toBeTrue();
});

test('a user cannot delete their own account', function () {
    $admin = adminActor();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertRedirect();

    $this->assertModelExists($admin);
});

test('the last admin cannot be deleted, even by another privileged user', function () {
    // As with the toggle-active guard, the realistic way to reach this is a
    // custom role with users.delete but not the admin role itself - an admin
    // acting alone would always be deleting themselves, already blocked by
    // the separate self-delete guard.
    $this->seed(RolePermissionSeeder::class);
    $role = Spatie\Permission\Models\Role::create(['name' => 'people-manager']);
    $role->givePermissionTo('users.delete', 'users.view');

    $manager = User::factory()->create();
    $manager->assignRole('people-manager');

    $soleAdmin = adminActor();

    $this->actingAs($manager)
        ->delete(route('admin.users.destroy', $soleAdmin))
        ->assertRedirect();

    $this->assertModelExists($soleAdmin);
});

test('a non-last admin can be deleted', function () {
    $admin = adminActor();
    $secondAdmin = adminActor();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $secondAdmin))
        ->assertRedirect();

    $this->assertModelMissing($secondAdmin);
});

test('toggling active status works on non-admin users', function () {
    $admin = adminActor();
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    $this->actingAs($admin)->patch(route('admin.users.toggle-active', $editor))->assertRedirect();
    expect($editor->refresh()->is_active)->toBeFalse();
});

test('the last active admin cannot be deactivated, even by another privileged user', function () {
    // A custom role with users.edit but not the admin role itself - the
    // realistic way this guard gets exercised, since editors don't have
    // users.edit by default and an admin acting alone would always be
    // deactivating themselves (already blocked by the self-action guard).
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
    $role = Spatie\Permission\Models\Role::create(['name' => 'people-manager']);
    $role->givePermissionTo('users.edit', 'users.view');

    $manager = User::factory()->create();
    $manager->assignRole('people-manager');

    $soleAdmin = User::factory()->create();
    $soleAdmin->assignRole('admin');

    $this->actingAs($manager)
        ->patch(route('admin.users.toggle-active', $soleAdmin))
        ->assertRedirect();

    expect($soleAdmin->refresh()->is_active)->toBeTrue();
});
