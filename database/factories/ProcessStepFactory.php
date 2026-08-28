<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessStepFactory extends Factory
{
    public function definition(): array
    {
        return [
            'step_number' => fake()->unique()->numberBetween(1, 10),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(12),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
