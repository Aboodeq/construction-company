<?php

namespace Database\Factories;

use App\Models\QuoteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteRequestFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quote_request_id' => QuoteRequest::factory(),
            'file_path' => 'placeholder/quote-file-'.fake()->numberBetween(1, 10).'.jpg',
            'type' => fake()->randomElement(['image', 'plan']),
        ];
    }
}
