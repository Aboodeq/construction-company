<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'نصائح التشطيب',
                'اتجاهات التصميم',
                'أخبار الشركة',
                'أدلة إرشادية',
                'مشاريعنا',
            ]),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
