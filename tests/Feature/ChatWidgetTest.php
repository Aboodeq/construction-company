<?php

use App\Models\ChatConversation;

function chatVisitorToken(): string
{
    return bin2hex(random_bytes(20));
}

test('starting a chat creates a conversation for a new visitor token', function () {
    $token = chatVisitorToken();

    $response = $this->postJson(route('chat.start'), ['visitor_token' => $token]);

    $response->assertOk()->assertJsonStructure(['conversation_id', 'messages']);
    $this->assertDatabaseHas('chat_conversations', ['visitor_token' => $token, 'status' => 'open']);
});

test('starting a chat again with the same token resumes the same conversation', function () {
    $token = chatVisitorToken();

    $first = $this->postJson(route('chat.start'), ['visitor_token' => $token])->json('conversation_id');
    $second = $this->postJson(route('chat.start'), ['visitor_token' => $token])->json('conversation_id');

    expect($second)->toBe($first);
    expect(ChatConversation::count())->toBe(1);
});

test('a visitor can send a message with a valid token', function () {
    $token = chatVisitorToken();
    $conversation = ChatConversation::factory()->create(['visitor_token' => $token]);

    $response = $this->postJson(route('chat.send', $conversation), [
        'visitor_token' => $token,
        'message' => 'Hello, I need help',
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('chat_messages', [
        'chat_conversation_id' => $conversation->id,
        'sender_type' => 'visitor',
        'message' => 'Hello, I need help',
    ]);
});

test('sending a message with a mismatched visitor token is forbidden', function () {
    $conversation = ChatConversation::factory()->create(['visitor_token' => chatVisitorToken()]);

    $this->postJson(route('chat.send', $conversation), [
        'visitor_token' => chatVisitorToken(),
        'message' => 'Trying to impersonate another visitor',
    ])->assertForbidden();
});

test('polling only returns messages after the given id', function () {
    $token = chatVisitorToken();
    $conversation = ChatConversation::factory()->create(['visitor_token' => $token]);
    $first = $conversation->messages()->create(['sender_type' => 'admin', 'message' => 'Older message']);
    $second = $conversation->messages()->create(['sender_type' => 'admin', 'message' => 'Newer message']);

    $response = $this->getJson(route('chat.poll', $conversation) . "?visitor_token={$token}&after={$first->id}");

    $response->assertOk();
    $ids = collect($response->json('messages'))->pluck('id');
    expect($ids)->toContain($second->id)->not->toContain($first->id);
});

test('polling with a mismatched visitor token is forbidden', function () {
    $conversation = ChatConversation::factory()->create(['visitor_token' => chatVisitorToken()]);

    $this->getJson(route('chat.poll', $conversation) . '?visitor_token=' . chatVisitorToken())
        ->assertForbidden();
});
