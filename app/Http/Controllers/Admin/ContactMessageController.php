<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('contact-messages.view');

        $messagesQuery = ContactMessage::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($request->input('filter') === 'unread', fn ($query) => $query->where('is_read', false))
            ->when($request->input('filter') === 'unreplied', fn ($query) => $query->where('is_replied', false));

        $messages = $messagesQuery->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'unreplied' => ContactMessage::where('is_replied', false)->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $this->authorize('contact-messages.view');

        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function toggleReplied(ContactMessage $contactMessage)
    {
        $this->authorize('contact-messages.view');

        $contactMessage->update(['is_replied' => ! $contactMessage->is_replied]);

        return back()->with('success', $contactMessage->is_replied ? 'تم وضع علامة "تم الرد".' : 'تم إلغاء علامة "تم الرد".');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $this->authorize('contact-messages.delete');

        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'تم حذف الرسالة.');
    }
}
