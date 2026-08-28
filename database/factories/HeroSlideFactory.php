<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HeroSlideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(8),
            'button_text' => fake()->boolean(70) ? 'اطلب عرض سعر' : null,
            'button_url' => fake()->boolean(70) ? '/quote-request' : null,
            'order' => fake()->numberBetween(0, 10),
            'status' => 'published',
        ];
    }
}
