<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use Illuminate\Http\Request;

class ChatWidgetController extends Controller
{
    /**
     * Start or resume a conversation for a visitor token generated client-side.
     */
    public function start(Request $request)
    {
        $data = $request->validate([
            'visitor_token' => ['required', 'string', 'size:40'],
            'visitor_name' => ['nullable', 'string', 'max:255'],
            'visitor_email' => ['nullable', 'email', 'max:255'],
        ]);

        $conversation = ChatConversation::firstOrCreate(
            ['visitor_token' => $data['visitor_token']],
            [
                'visitor_name' => $data['visitor_name'] ?? null,
                'visitor_email' => $data['visitor_email'] ?? null,
                'status' => 'open',
            ]
        );

        if (($data['visitor_name'] ?? null) && ! $conversation->visitor_name) {
            $conversation->update(['visitor_name' => $data['visitor_name']]);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $conversation->messages()->get(['id', 'sender_type', 'message', 'created_at']),
        ]);
    }

    /**
     * Send a message as the visitor.
     */
    public function send(Request $request, ChatConversation $conversation)
    {
        $data = $request->validate([
            'visitor_token' => ['required', 'string', 'size:40'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        abort_unless(hash_equals($conversation->visitor_token, $data['visitor_token']), 403);

        if ($conversation->status === 'closed') {
            $conversation->update(['status' => 'open']);
        }

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'message' => $data['message'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'message' => $message->only(['id', 'sender_type', 'message', 'created_at']),
        ]);
    }

    /**
     * Poll for messages added after a given message id (admin replies).
     */
    public function poll(Request $request, ChatConversation $conversation)
    {
        $data = $request->validate([
            'visitor_token' => ['required', 'string', 'size:40'],
            'after' => ['nullable', 'integer'],
        ]);

        abort_unless(hash_equals($conversation->visitor_token, $data['visitor_token']), 403);

        $messages = $conversation->messages()
            ->when($data['after'] ?? null, fn ($query, $after) => $query->where('id', '>', $after))
            ->get(['id', 'sender_type', 'message', 'created_at']);

        return response()->json(['messages' => $messages]);
    }
}
