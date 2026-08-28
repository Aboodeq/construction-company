<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->boolean(60) ? fake()->numerify('05########') : null,
            'subject' => fake()->randomElement(['استفسار عن خدمة', 'طلب معلومات', 'شكوى', 'اقتراح', null]),
            'message' => fake()->paragraph(3),
            'is_read' => fake()->boolean(60),
            'is_replied' => fake()->boolean(30),
        ];
    }
}
