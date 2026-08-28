<?php

use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function contactMessageUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.contact-messages.index'))->assertRedirect(route('login'));
});

test('admin can view the contact messages index', function () {
    $admin = contactMessageUserWithRole('admin');
    ContactMessage::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.contact-messages.index'))
        ->assertOk()
        ->assertSee('رسائل التواصل');
});

test('viewing an unread message marks it as read', function () {
    $admin = contactMessageUserWithRole('admin');
    $message = ContactMessage::factory()->create(['is_read' => false]);

    $this->actingAs($admin)->get(route('admin.contact-messages.show', $message))->assertOk();

    expect($message->refresh()->is_read)->toBeTrue();
});

test('editor without contact-messages permissions is forbidden', function () {
    $editor = contactMessageUserWithRole('editor');
    $message = ContactMessage::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.contact-messages.show', $message))
        ->assertForbidden();
});

test('admin can toggle the replied flag', function () {
    $admin = contactMessageUserWithRole('admin');
    $message = ContactMessage::factory()->create(['is_replied' => false]);

    $this->actingAs($admin)->patch(route('admin.contact-messages.toggle-replied', $message))->assertRedirect();
    expect($message->refresh()->is_replied)->toBeTrue();
});

test('admin can delete a message', function () {
    $admin = contactMessageUserWithRole('admin');
    $message = ContactMessage::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.contact-messages.destroy', $message))
        ->assertRedirect(route('admin.contact-messages.index'));

    $this->assertModelMissing($message);
});
