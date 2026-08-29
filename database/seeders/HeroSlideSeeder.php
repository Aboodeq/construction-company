<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'نبني رؤيتك بأيدٍ خبيرة',
                'subtitle' => 'تشطيبات فاخرة ومقاولات احترافية لكل مساحاتك',
                'button_text' => 'اطلب عرض سعر',
                'button_url' => '#contact',
                'order' => 1,
            ],
            [
                'title' => 'تفاصيل دقيقة، نتائج استثنائية',
                'subtitle' => 'أكثر من 200 مشروع منجز بثقة عملائنا',
                'button_text' => 'استعرض أعمالنا',
                'button_url' => '#projects',
                'order' => 2,
            ],
            [
                'title' => 'من التصميم إلى التسليم',
                'subtitle' => 'نرافقك في كل خطوة من رحلة مشروعك',
                'button_text' => 'احجز معاينة مجانية',
                'button_url' => '#contact',
                'order' => 3,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::firstOrCreate(
                ['title' => $slide['title']],
                [
                    'subtitle' => $slide['subtitle'],
                    'button_text' => $slide['button_text'],
                    'button_url' => $slide['button_url'],
                    'order' => $slide['order'],
                    'status' => 'published',
                ]
            );
        }
    }
}
