<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'home_hero_intro',
                'title' => 'نبني رؤيتك بأيدٍ خبيرة',
                'subtitle' => 'تشطيبات فاخرة ومقاولات احترافية',
                'content' => null,
                'extra_data' => null,
            ],
            [
                'key' => 'why_choose_us',
                'title' => 'لماذا تختارنا',
                'subtitle' => 'التزام بالجودة في كل مرحلة',
                'content' => 'نجمع بين الخبرة الطويلة والدقة في التنفيذ لنقدم لك مشروعًا يفوق توقعاتك، من التصميم الأولي حتى التسليم النهائي.',
                'extra_data' => [
                    'points' => [
                        ['icon' => 'shield-check', 'title' => 'ضمان الجودة', 'description' => 'نلتزم بأعلى معايير الجودة في كل مشروع.'],
                        ['icon' => 'clock', 'title' => 'الالتزام بالوقت', 'description' => 'تسليم المشاريع في الموعد المحدد دون تأخير.'],
                        ['icon' => 'users', 'title' => 'فريق محترف', 'description' => 'فريق من المهندسين والفنيين ذوي الخبرة العالية.'],
                    ],
                ],
            ],
            [
                'key' => 'about_story',
                'title' => 'قصتنا',
                'subtitle' => 'رحلة من الشغف إلى الاحتراف',
                'content' => 'بدأنا رحلتنا بشغف حقيقي لتحويل المساحات إلى تحف معمارية، واليوم نفخر بعشرات المشاريع الناجحة التي تحمل بصمتنا في كل تفصيلة.',
                'extra_data' => null,
            ],
            [
                'key' => 'about_vision',
                'title' => 'رؤيتنا',
                'subtitle' => null,
                'content' => 'أن نكون الخيار الأول للتشطيبات الفاخرة والمقاولات في المنطقة.',
                'extra_data' => null,
            ],
            [
                'key' => 'about_mission',
                'title' => 'رسالتنا',
                'subtitle' => null,
                'content' => 'تقديم حلول تشطيب ومقاولات متكاملة تجمع بين الجودة، الابتكار، والالتزام بالمواعيد.',
                'extra_data' => null,
            ],
            [
                'key' => 'home_contact_cta',
                'title' => 'جاهز لبدء مشروعك؟',
                'subtitle' => 'تواصل معنا اليوم واحصل على استشارة مجانية',
                'content' => null,
                'extra_data' => null,
            ],
        ];

        foreach ($sections as $section) {
            PageSection::firstOrCreate(
                ['key' => $section['key']],
                [
                    'title' => $section['title'],
                    'subtitle' => $section['subtitle'],
                    'content' => $section['content'],
                    'extra_data' => $section['extra_data'],
                ]
            );
        }
    }
}
