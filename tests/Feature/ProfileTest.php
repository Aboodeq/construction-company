<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function adminUser(): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

test('profile page is displayed', function () {
    $response = $this
        ->actingAs(adminUser())
        ->get(route('admin.profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = adminUser();

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.profile.edit'));

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = adminUser();

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.profile.edit'));

    $this->assertNotNull($user->refresh()->email_verified_at);
});
