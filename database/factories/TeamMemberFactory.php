<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory
{
    public function definition(): array
    {
        $positions = [
            'المدير التنفيذي',
            'مدير المشاريع',
            'مهندس معماري',
            'مهندس تنفيذ',
            'مصمم داخلي',
            'مشرف موقع',
            'مدير التسويق',
        ];

        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement($positions),
            'image' => null,
            'bio' => fake()->sentence(15),
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/' . fake()->userName(),
                'twitter' => fake()->boolean(50) ? 'https://twitter.com/' . fake()->userName() : null,
            ],
            'order' => fake()->numberBetween(0, 10),
            'status' => 'published',
        ];
    }
}
