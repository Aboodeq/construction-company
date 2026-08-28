<?php

use App\Mail\LeadReplyMail;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Mail;

function emailReplyUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('admin can send an email reply to a contact message', function () {
    Mail::fake();
    $admin = emailReplyUserWithRole('admin');
    $message = ContactMessage::factory()->create(['is_replied' => false]);

    $this->actingAs($admin)
        ->post(route('admin.contact-messages.reply-email', $message), [
            'subject' => 'رد على استفسارك',
            'message' => 'شكرًا لتواصلك معنا، سنعاود الاتصال بك قريبًا.',
        ])
        ->assertRedirect();

    Mail::assertSent(LeadReplyMail::class, fn ($mail) => $mail->hasTo($message->email));

    $this->assertDatabaseHas('email_replies', [
        'repliable_type' => ContactMessage::class,
        'repliable_id' => $message->id,
        'to_email' => $message->email,
        'sender_id' => $admin->id,
        'subject' => 'رد على استفسارك',
    ]);

    expect($message->refresh()->is_replied)->toBeTrue();
});

test('admin can send an email reply to a quote request with an email', function () {
    Mail::fake();
    $admin = emailReplyUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create(['email' => 'lead@example.com']);

    $this->actingAs($admin)
        ->post(route('admin.quote-requests.reply-email', $quoteRequest), [
            'subject' => 'عرض السعر الخاص بك',
            'message' => 'أرفقنا لك تفاصيل العرض.',
        ])
        ->assertRedirect();

    Mail::assertSent(LeadReplyMail::class, fn ($mail) => $mail->hasTo('lead@example.com'));
    $this->assertDatabaseHas('email_replies', [
        'repliable_type' => QuoteRequest::class,
        'repliable_id' => $quoteRequest->id,
    ]);
});

test('replying to a quote request without an email fails', function () {
    $admin = emailReplyUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create(['email' => null]);

    $this->actingAs($admin)
        ->post(route('admin.quote-requests.reply-email', $quoteRequest), [
            'subject' => 'test',
            'message' => 'test',
        ])
        ->assertStatus(422);
});

test('admin can send an email reply to a booking with an email', function () {
    Mail::fake();
    $admin = emailReplyUserWithRole('admin');
    $booking = Booking::factory()->create(['email' => 'client@example.com']);

    $this->actingAs($admin)
        ->post(route('admin.bookings.reply-email', $booking), [
            'subject' => 'تأكيد موعد الزيارة',
            'message' => 'تم تأكيد موعدكم يوم الخميس القادم.',
        ])
        ->assertRedirect();

    Mail::assertSent(LeadReplyMail::class, fn ($mail) => $mail->hasTo('client@example.com'));
    $this->assertDatabaseHas('email_replies', [
        'repliable_type' => Booking::class,
        'repliable_id' => $booking->id,
    ]);
});

test('editor without lead permissions cannot send an email reply', function () {
    $editor = emailReplyUserWithRole('editor');
    $message = ContactMessage::factory()->create();

    $this->actingAs($editor)
        ->post(route('admin.contact-messages.reply-email', $message), [
            'subject' => 'test',
            'message' => 'test',
        ])
        ->assertForbidden();
});

test('email reply requires a subject and message', function () {
    $admin = emailReplyUserWithRole('admin');
    $message = ContactMessage::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.contact-messages.reply-email', $message), [])
        ->assertSessionHasErrors(['subject', 'message']);
});
