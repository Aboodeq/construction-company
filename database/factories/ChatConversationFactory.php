<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChatConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'visitor_token' => bin2hex(random_bytes(20)),
            'visitor_name' => fake()->boolean(70) ? fake()->name() : null,
            'visitor_email' => fake()->boolean(50) ? fake()->safeEmail() : null,
            'status' => fake()->randomElement(['open', 'closed']),
            'last_message_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
