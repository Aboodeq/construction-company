<?php

namespace Database\Seeders;

use App\Models\FinishingLevel;
use Illuminate\Database\Seeder;

class FinishingLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'اقتصادي',
                'multiplier' => 1.00,
                'description' => 'تشطيب عملي بجودة جيدة وأسعار مناسبة.',
                'order' => 1,
            ],
            [
                'name' => 'متوسط',
                'multiplier' => 1.40,
                'description' => 'توازن بين الجودة والتكلفة مع خامات محسّنة.',
                'order' => 2,
            ],
            [
                'name' => 'فاخر',
                'multiplier' => 1.90,
                'description' => 'خامات عالية الجودة وتشطيبات دقيقة.',
                'order' => 3,
            ],
            [
                'name' => 'سوبر لوكس',
                'multiplier' => 2.50,
                'description' => 'أفخم الخامات وأعلى مستويات الدقة والتفصيل.',
                'order' => 4,
            ],
        ];

        foreach ($levels as $level) {
            FinishingLevel::firstOrCreate(
                ['name' => $level['name']],
                [
                    'multiplier' => $level['multiplier'],
                    'description' => $level['description'],
                    'order' => $level['order'],
                ]
            );
        }
    }
}
