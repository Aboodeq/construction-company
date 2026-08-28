<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyStatisticFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => fake()->unique()->randomElement(['سنوات الخبرة', 'مشروع منجز', 'عميل راضٍ', 'مهندس ومتخصص']),
            'number' => fake()->numberBetween(5, 300),
            'suffix' => '+',
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
