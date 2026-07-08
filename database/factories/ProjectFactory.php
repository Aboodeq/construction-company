<?php

namespace Database\Factories;

use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $projectNames = [
            'فيلا العائلة الملكية',
            'شقة الواحة السكنية',
            'مجمع مكاتب النخبة',
            'معرض الأناقة التجاري',
            'مطعم الأصالة',
            'فيلا الياسمين',
            'برج السلام السكني',
            'كافيه المدينة',
            'مكتب الاستشارات الهندسية',
            'فيلا الواحة الخضراء',
            'مجمع تجاري الروضة',
            'شقة الأفق الذهبي',
        ];

        return [
            'project_category_id' => ProjectCategory::inRandomOrder()->first()?->id
                ?? ProjectCategory::factory(),
            'title' => fake()->unique()->randomElement($projectNames),
            'client_name' => fake()->boolean(60) ? fake()->name() : null,
            'location' => fake()->randomElement(['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'الخبر']),
            'area' => fake()->numberBetween(150, 1200),
            'completion_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'duration' => fake()->randomElement(['شهرين', '3 أشهر', '4 أشهر', '6 أشهر', 'سنة واحدة']),
            'description' => fake()->paragraphs(4, true),
            'cover_image' => 'placeholder/project-' . fake()->numberBetween(1, 10) . '.jpg',
            'is_featured' => fake()->boolean(30),
            'status' => 'published',
            'order' => fake()->numberBetween(0, 20),
        ];
    }
}
