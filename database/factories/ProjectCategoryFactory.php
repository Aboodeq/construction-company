<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'فلل',
                'شقق',
                'مكاتب',
                'محلات تجارية',
                'مطاعم ومقاهي',
                'سكني',
                'تجاري',
            ]),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
