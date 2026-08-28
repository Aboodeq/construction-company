<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('05########'),
            'email' => fake()->boolean(60) ? fake()->safeEmail() : null,
            'preferred_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'preferred_time' => fake()->randomElement(['09:00 صباحًا', '11:00 صباحًا', '02:00 مساءً', '05:00 مساءً']),
            'city' => fake()->randomElement(['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'الخبر']),
            'address' => fake()->boolean(70) ? fake()->streetAddress() : null,
            'notes' => fake()->boolean(50) ? fake()->sentence(10) : null,
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
        ];
    }
}
