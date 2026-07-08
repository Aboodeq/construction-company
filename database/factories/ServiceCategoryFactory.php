<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'التشطيبات الداخلية',
                'أعمال البناء',
                'الترميم والتجديد',
                'الأعمال الكهربائية والصحية',
            ]),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
