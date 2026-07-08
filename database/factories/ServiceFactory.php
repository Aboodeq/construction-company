<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'تشطيب فلل فاخر',
            'تشطيب شقق سكنية',
            'مشاريع تجارية ومكاتب',
            'أعمال الجبس والديكور',
            'الدهانات الداخلية والخارجية',
            'أعمال السباكة',
            'التمديدات الكهربائية',
            'تركيب الأرضيات',
            'الرخام والحجر الطبيعي',
            'الترميم الشامل',
        ];

        return [
            'service_category_id' => ServiceCategory::inRandomOrder()->first()?->id
                ?? ServiceCategory::factory(),
            'title' => fake()->unique()->randomElement($titles),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(4, true),
            'icon' => null,
            'featured_image' => null,
            'process_steps' => [
                ['step' => 1, 'title' => 'الاستشارة الأولية', 'description' => fake()->sentence()],
                ['step' => 2, 'title' => 'التصميم والتخطيط', 'description' => fake()->sentence()],
                ['step' => 3, 'title' => 'التنفيذ', 'description' => fake()->sentence()],
                ['step' => 4, 'title' => 'التسليم النهائي', 'description' => fake()->sentence()],
            ],
            'is_featured' => fake()->boolean(30),
            'status' => 'published',
            'order' => fake()->numberBetween(0, 20),
        ];
    }
}
