<?php

use App\Models\ChatConversation;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function chatUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.chats.index'))->assertRedirect(route('login'));
});

test('admin can view the chats index', function () {
    $admin = chatUserWithRole('admin');
    ChatConversation::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.chats.index'))
        ->assertOk()
        ->assertSee('المحادثات المباشرة');
});

test('editor without chats permissions is forbidden', function () {
    $editor = chatUserWithRole('editor');
    $conversation = ChatConversation::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.chats.show', $conversation))
        ->assertForbidden();
});

test('viewing a conversation marks unread visitor messages as read', function () {
    $admin = chatUserWithRole('admin');
    $conversation = ChatConversation::factory()->create();
    $message = $conversation->messages()->create([
        'sender_type' => 'visitor',
        'message' => 'Hello there',
    ]);

    $this->actingAs($admin)->get(route('admin.chats.show', $conversation))->assertOk();

    expect($message->refresh()->read_at)->not->toBeNull();
});

test('admin can reply to a conversation', function () {
    $admin = chatUserWithRole('admin');
    $conversation = ChatConversation::factory()->create(['assigned_to' => null]);

    $this->actingAs($admin)
        ->post(route('admin.chats.reply', $conversation), ['message' => 'Thanks for reaching out'])
        ->assertRedirect();

    $this->assertDatabaseHas('chat_messages', [
        'chat_conversation_id' => $conversation->id,
        'sender_type' => 'admin',
        'sender_id' => $admin->id,
        'message' => 'Thanks for reaching out',
    ]);

    expect($conversation->refresh()->assigned_to)->toBe($admin->id);
});

test('admin can toggle a conversation status', function () {
    $admin = chatUserWithRole('admin');
    $conversation = ChatConversation::factory()->create(['status' => 'open']);

    $this->actingAs($admin)->patch(route('admin.chats.toggle-status', $conversation))->assertRedirect();
    expect($conversation->refresh()->status)->toBe('closed');
});

test('admin can delete a conversation', function () {
    $admin = chatUserWithRole('admin');
    $conversation = ChatConversation::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.chats.destroy', $conversation))
        ->assertRedirect(route('admin.chats.index'));

    $this->assertModelMissing($conversation);
});
