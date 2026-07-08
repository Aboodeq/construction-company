<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image_path' => 'placeholder/project-gallery-' . fake()->numberBetween(1, 15) . '.jpg',
            'type' => 'gallery',
            'order' => fake()->numberBetween(0, 5),
        ];
    }

    /**
     * State: a "before" image.
     */
    public function before(): static
    {
        return $this->state(fn() => [
            'image_path' => 'placeholder/before-' . fake()->numberBetween(1, 10) . '.jpg',
            'type' => 'before',
        ]);
    }

    /**
     * State: an "after" image.
     */
    public function after(): static
    {
        return $this->state(fn() => [
            'image_path' => 'placeholder/after-' . fake()->numberBetween(1, 10) . '.jpg',
            'type' => 'after',
        ]);
    }
}
