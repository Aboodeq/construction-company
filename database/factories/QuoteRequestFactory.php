<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('05########'),
            'email' => fake()->boolean(70) ? fake()->safeEmail() : null,
            'project_type' => fake()->randomElement(['تشطيب فيلا', 'تشطيب شقة', 'مشروع تجاري', 'ترميم']),
            'city' => fake()->randomElement(['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'الخبر']),
            'area' => fake()->numberBetween(100, 1000),
            'estimated_budget' => fake()->randomElement(['أقل من 100 ألف', '100 - 300 ألف', '300 - 500 ألف', 'أكثر من 500 ألف']),
            'description' => fake()->boolean(70) ? fake()->sentence(15) : null,
            'status' => fake()->randomElement(['new', 'read', 'in_progress', 'closed']),
        ];
    }
}
