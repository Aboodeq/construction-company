<?php

use App\Models\CompanyStatistic;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

test('the homepage renders with published content', function () {
    HeroSlide::factory()->create(['title' => 'شريحة الاختبار', 'status' => 'published']);
    CompanyStatistic::factory()->create(['label' => 'إحصائية الاختبار']);
    Service::factory()->create(['title' => 'خدمة الاختبار', 'status' => 'published']);
    Project::factory()->create(['title' => 'مشروع الاختبار', 'status' => 'published']);
    Testimonial::factory()->create(['review' => 'رأي تجريبي رائع', 'status' => 'published']);
    Faq::factory()->create(['status' => 'published']);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('شريحة الاختبار')
        ->assertSee('إحصائية الاختبار')
        ->assertSee('خدمة الاختبار')
        ->assertSee('مشروع الاختبار')
        ->assertSee('رأي تجريبي رائع');
});

test('a draft hero slide is not shown on the homepage', function () {
    HeroSlide::factory()->create(['title' => 'شريحة مسودة مخفية', 'status' => 'draft']);

    $this->get(route('home'))->assertOk()->assertDontSee('شريحة مسودة مخفية');
});
