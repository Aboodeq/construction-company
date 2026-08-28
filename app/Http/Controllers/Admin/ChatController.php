<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('chats.view');

        $conversationsQuery = ChatConversation::query()
            ->withUnreadCount()
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            });

        $conversations = $conversationsQuery
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => ChatConversation::count(),
            'open' => ChatConversation::where('status', 'open')->count(),
            'unread' => ChatConversation::withUnreadCount()->get()->filter(fn ($c) => $c->unread_count > 0)->count(),
        ];

        return view('admin.chats.index', compact('conversations', 'stats'));
    }

    public function show(ChatConversation $conversation)
    {
        $this->authorize('chats.view');

        $conversation->messages()->where('sender_type', 'visitor')->whereNull('read_at')->update(['read_at' => now()]);
        $conversation->load('messages', 'assignedTo');

        return view('admin.chats.show', compact('conversation'));
    }

    public function reply(Request $request, ChatConversation $conversation)
    {
        $this->authorize('chats.reply');

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $conversation->messages()->create([
            'sender_type' => 'admin',
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'assigned_to' => $conversation->assigned_to ?? $request->user()->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function poll(Request $request, ChatConversation $conversation)
    {
        $this->authorize('chats.view');

        $after = $request->integer('after');

        $messages = $conversation->messages()
            ->when($after, fn ($query) => $query->where('id', '>', $after))
            ->get(['id', 'sender_type', 'message', 'created_at']);

        $conversation->messages()->where('sender_type', 'visitor')->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    public function toggleStatus(ChatConversation $conversation)
    {
        $this->authorize('chats.reply');

        $conversation->update(['status' => $conversation->status === 'open' ? 'closed' : 'open']);

        return back()->with('success', $conversation->status === 'open' ? 'تم إعادة فتح المحادثة.' : 'تم إغلاق المحادثة.');
    }

    public function destroy(ChatConversation $conversation)
    {
        $this->authorize('chats.delete');

        $conversation->delete();

        return redirect()
            ->route('admin.chats.index')
            ->with('success', 'تم حذف المحادثة.');
    }
}
