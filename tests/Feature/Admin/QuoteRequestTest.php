<?php

use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function quoteRequestUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.quote-requests.index'))->assertRedirect(route('login'));
});

test('admin can view the quote requests index', function () {
    $admin = quoteRequestUserWithRole('admin');
    QuoteRequest::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.quote-requests.index'))
        ->assertOk()
        ->assertSee('طلبات الأسعار');
});

test('viewing a new request marks it as read', function () {
    $admin = quoteRequestUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create(['status' => 'new']);

    $this->actingAs($admin)
        ->get(route('admin.quote-requests.show', $quoteRequest))
        ->assertOk();

    expect($quoteRequest->refresh()->status)->toBe('read');
});

test('a request already past new is not reset back to read', function () {
    $admin = quoteRequestUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create(['status' => 'closed']);

    $this->actingAs($admin)->get(route('admin.quote-requests.show', $quoteRequest));

    expect($quoteRequest->refresh()->status)->toBe('closed');
});

test('editor without quote-requests permissions is forbidden', function () {
    $editor = quoteRequestUserWithRole('editor');
    $quoteRequest = QuoteRequest::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.quote-requests.show', $quoteRequest))
        ->assertForbidden();
});

test('admin can update the status', function () {
    $admin = quoteRequestUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create(['status' => 'read']);

    $this->actingAs($admin)
        ->patch(route('admin.quote-requests.update-status', $quoteRequest), ['status' => 'in_progress'])
        ->assertRedirect();

    expect($quoteRequest->refresh()->status)->toBe('in_progress');
});

test('admin can delete a quote request', function () {
    $admin = quoteRequestUserWithRole('admin');
    $quoteRequest = QuoteRequest::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.quote-requests.destroy', $quoteRequest))
        ->assertRedirect(route('admin.quote-requests.index'));

    $this->assertModelMissing($quoteRequest);
});

test('admin can export quote requests as csv', function () {
    $admin = quoteRequestUserWithRole('admin');
    QuoteRequest::factory()->count(2)->create();

    $response = $this->actingAs($admin)->get(route('admin.quote-requests.export'));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');
});
