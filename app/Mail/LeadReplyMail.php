<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $toName,
        public string $emailSubject,
        public string $body,
        public User $sender,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
            replyTo: [$this->sender->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-reply',
        );
    }
}
