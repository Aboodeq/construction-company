<?php

namespace Database\Factories;

use App\Models\ChatConversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'chat_conversation_id' => ChatConversation::factory(),
            'sender_type' => 'visitor',
            'message' => fake()->sentence(10),
            'read_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-1 week', 'now') : null,
        ];
    }
}
