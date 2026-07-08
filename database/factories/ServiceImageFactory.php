<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image_path' => 'placeholder/service-' . fake()->numberBetween(1, 10) . '.jpg',
            'alt_text' => fake()->sentence(4),
            'order' => fake()->numberBetween(0, 5),
        ];
    }
}
