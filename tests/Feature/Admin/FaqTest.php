<?php

use App\Models\Faq;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

function faqUserWithRole(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('guests are redirected to login', function () {
    $this->get(route('admin.faqs.index'))->assertRedirect(route('login'));
});

test('admin can view the faqs index', function () {
    $admin = faqUserWithRole('admin');
    Faq::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.faqs.index'))
        ->assertOk()
        ->assertSee('الأسئلة الشائعة');
});

test('editor can view but not delete a faq', function () {
    $editor = faqUserWithRole('editor');
    $faq = Faq::factory()->create();

    $this->actingAs($editor)
        ->get(route('admin.faqs.edit', $faq))
        ->assertOk();

    $this->actingAs($editor)
        ->delete(route('admin.faqs.destroy', $faq))
        ->assertForbidden();

    $this->assertModelExists($faq);
});

test('admin can create a faq and is redirected straight to the index', function () {
    $admin = faqUserWithRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.faqs.store'), [
        'category' => 'عام',
        'question' => 'Test question?',
        'answer' => 'Test answer.',
        'status' => 'published',
        'order' => 0,
    ]);

    $response->assertRedirect(route('admin.faqs.index'));
    expect(Faq::where('question', 'Test question?')->exists())->toBeTrue();
});

test('admin can update a faq', function () {
    $admin = faqUserWithRole('admin');
    $faq = Faq::factory()->create();

    $this->actingAs($admin)->put(route('admin.faqs.update', $faq), [
        'category' => 'محدث',
        'question' => 'Updated question?',
        'answer' => $faq->answer,
        'status' => 'draft',
        'order' => 3,
    ])->assertRedirect(route('admin.faqs.index'));

    $faq->refresh();
    expect($faq->question)->toBe('Updated question?');
    expect($faq->status)->toBe('draft');
});

test('the category filter narrows results', function () {
    $admin = faqUserWithRole('admin');
    Faq::factory()->create(['category' => 'الأسعار', 'question' => 'Pricing question?']);
    Faq::factory()->create(['category' => 'عام', 'question' => 'General question?']);

    $response = $this->actingAs($admin)->get(route('admin.faqs.index', ['category' => 'الأسعار']));

    $response->assertSee('Pricing question?');
    $response->assertDontSee('General question?');
});

test('admin can delete a faq', function () {
    $admin = faqUserWithRole('admin');
    $faq = Faq::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.faqs.destroy', $faq))
        ->assertRedirect(route('admin.faqs.index'));

    $this->assertModelMissing($faq);
});

test('toggling published status works', function () {
    $admin = faqUserWithRole('admin');
    $faq = Faq::factory()->create(['status' => 'draft']);

    $this->actingAs($admin)->patch(route('admin.faqs.toggle-published', $faq))->assertRedirect();
    expect($faq->refresh()->status)->toBe('published');
});
