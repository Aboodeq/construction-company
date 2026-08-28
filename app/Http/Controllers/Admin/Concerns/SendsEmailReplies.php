<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Mail\LeadReplyMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

trait SendsEmailReplies
{
    /**
     * Validate, send, and log an email reply to a lead (contact message, quote request, or booking).
     */
    protected function sendEmailReply(Request $request, Model $repliable, string $toEmail, string $toName): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $sender = $request->user();

        Mail::to($toEmail)->send(new LeadReplyMail($toName, $data['subject'], $data['message'], $sender));

        $repliable->emailReplies()->create([
            'sender_id' => $sender->id,
            'to_email' => $toEmail,
            'to_name' => $toName,
            'subject' => $data['subject'],
            'body' => $data['message'],
        ]);

        return back()->with('success', 'تم إرسال الرد عبر البريد الإلكتروني.');
    }
}
