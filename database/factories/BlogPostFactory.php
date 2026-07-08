<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'أهم 5 اتجاهات في تشطيبات الفلل لهذا العام',
            'كيف تختار الطلاء المناسب لمنزلك؟',
            'دليلك الشامل لتشطيب المطبخ الفاخر',
            'الفرق بين الرخام الطبيعي والحجر الصناعي',
            'نصائح قبل البدء بمشروع الترميم',
            'كيف تحسب ميزانية تشطيب شقتك بدقة؟',
            'أفكار إضاءة عصرية للمساحات الداخلية',
            'خطوات اختيار مقاول التشطيب المناسب',
        ];

        return [
            'blog_category_id' => BlogCategory::inRandomOrder()->first()?->id
                ?? BlogCategory::factory(),
            'author_id' => User::inRandomOrder()->first()?->id,
            'title' => fake()->unique()->randomElement($titles),
            'excerpt' => fake()->sentence(20),
            'content' => fake()->paragraphs(8, true),
            'featured_image' => 'placeholder/blog-' . fake()->numberBetween(1, 10) . '.jpg',
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'views_count' => fake()->numberBetween(10, 500),
        ];
    }
}
