<?php

use App\Models\Booking;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function bookingUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.bookings.index'))->assertRedirect(route('login'));
});

test('admin can view the bookings index', function () {
    $admin = bookingUserWithRole('admin');
    Booking::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.bookings.index'))
        ->assertOk()
        ->assertSee('الحجوزات');
});

test('editor without bookings permissions is forbidden', function () {
    $editor = bookingUserWithRole('editor');
    $booking = Booking::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.bookings.edit', $booking))
        ->assertForbidden();
});

test('admin can reschedule and update status', function () {
    $admin = bookingUserWithRole('admin');
    $booking = Booking::factory()->create(['status' => 'pending']);

    $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
        'preferred_date' => now()->addWeek()->format('Y-m-d'),
        'preferred_time' => '10:00 صباحًا',
        'city' => $booking->city,
        'status' => 'confirmed',
    ])->assertRedirect(route('admin.bookings.edit', $booking));

    expect($booking->refresh()->status)->toBe('confirmed');
});

test('admin can delete a booking', function () {
    $admin = bookingUserWithRole('admin');
    $booking = Booking::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.bookings.destroy', $booking))
        ->assertRedirect(route('admin.bookings.index'));

    $this->assertModelMissing($booking);
});
