<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        $reviews = [
            'تجربة رائعة من البداية للنهاية، الفريق محترف والتزم بالمواعيد المتفق عليها.',
            'جودة التنفيذ فاقت توقعاتي، أنصح بالتعامل معهم لأي مشروع تشطيب.',
            'اهتمام بالتفاصيل الدقيقة ونتيجة نهائية فخمة جدًا، شكرًا لكم.',
            'تعامل احترافي وأسعار مناسبة مقارنة بجودة العمل المقدمة.',
            'فريق متعاون ويستمع لملاحظات العميل، النتيجة كانت أفضل مما توقعت.',
        ];

        return [
            'project_id' => fake()->boolean(70)
                ? Project::inRandomOrder()->first()?->id
                : null,
            'client_name' => fake()->name(),
            'client_image' => null,
            'rating' => fake()->numberBetween(4, 5),
            'review' => fake()->randomElement($reviews),
            'is_featured' => fake()->boolean(40),
            'status' => 'published',
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
