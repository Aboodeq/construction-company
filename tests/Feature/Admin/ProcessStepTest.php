<?php

use App\Models\ProcessStep;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function processStepUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.process-steps.index'))->assertRedirect(route('login'));
});

test('admin can view the process steps index', function () {
    $admin = processStepUserWithRole('admin');
    ProcessStep::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.process-steps.index'))
        ->assertOk()
        ->assertSee('مراحل العمل');
});

test('editor without homepage.edit permission is forbidden', function () {
    $editor = processStepUserWithRole('editor');

    $this->actingAs($editor)
        ->get(route('admin.process-steps.index'))
        ->assertForbidden();
});

test('admin can create a process step', function () {
    $admin = processStepUserWithRole('admin');

    $this->actingAs($admin)->post(route('admin.process-steps.store'), [
        'step_number' => 1,
        'title' => 'التواصل والاستشارة',
        'description' => 'وصف الخطوة',
        'order' => 1,
    ])->assertRedirect(route('admin.process-steps.index'));

    $this->assertDatabaseHas('process_steps', ['title' => 'التواصل والاستشارة']);
});

test('admin can update a process step', function () {
    $admin = processStepUserWithRole('admin');
    $step = ProcessStep::factory()->create();

    $this->actingAs($admin)->put(route('admin.process-steps.update', $step), [
        'step_number' => $step->step_number,
        'title' => 'عنوان محدّث',
        'order' => $step->order,
    ])->assertRedirect();

    expect($step->refresh()->title)->toBe('عنوان محدّث');
});

test('admin can delete a process step', function () {
    $admin = processStepUserWithRole('admin');
    $step = ProcessStep::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.process-steps.destroy', $step))
        ->assertRedirect(route('admin.process-steps.index'));

    $this->assertModelMissing($step);
});
