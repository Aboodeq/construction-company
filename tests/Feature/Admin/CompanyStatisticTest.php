<?php

use App\Models\CompanyStatistic;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function companyStatisticUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.company-statistics.index'))->assertRedirect(route('login'));
});

test('admin can view the statistics index', function () {
    $admin = companyStatisticUserWithRole('admin');
    CompanyStatistic::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.company-statistics.index'))
        ->assertOk()
        ->assertSee('إحصائيات الشركة');
});

test('editor without homepage.edit permission is forbidden', function () {
    $editor = companyStatisticUserWithRole('editor');

    $this->actingAs($editor)
        ->get(route('admin.company-statistics.index'))
        ->assertForbidden();
});

test('admin can create a statistic', function () {
    $admin = companyStatisticUserWithRole('admin');

    $this->actingAs($admin)->post(route('admin.company-statistics.store'), [
        'label' => 'مشروع منجز',
        'number' => 250,
        'suffix' => '+',
        'order' => 1,
    ])->assertRedirect(route('admin.company-statistics.index'));

    $this->assertDatabaseHas('company_statistics', ['label' => 'مشروع منجز', 'number' => 250]);
});

test('admin can update a statistic', function () {
    $admin = companyStatisticUserWithRole('admin');
    $statistic = CompanyStatistic::factory()->create(['number' => 10]);

    $this->actingAs($admin)->put(route('admin.company-statistics.update', $statistic), [
        'label' => $statistic->label,
        'number' => 99,
        'order' => $statistic->order,
    ])->assertRedirect();

    expect($statistic->refresh()->number)->toBe(99);
});

test('admin can delete a statistic', function () {
    $admin = companyStatisticUserWithRole('admin');
    $statistic = CompanyStatistic::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.company-statistics.destroy', $statistic))
        ->assertRedirect(route('admin.company-statistics.index'));

    $this->assertModelMissing($statistic);
});
