<?php

namespace Database\Seeders;

use App\Models\CompanyStatistic;
use Illuminate\Database\Seeder;

class CompanyStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['label' => 'سنوات الخبرة', 'number' => 15, 'suffix' => '+', 'order' => 1],
            ['label' => 'مشروع منجز', 'number' => 200, 'suffix' => '+', 'order' => 2],
            ['label' => 'عميل راضٍ', 'number' => 180, 'suffix' => '+', 'order' => 3],
            ['label' => 'مهندس ومتخصص', 'number' => 25, 'suffix' => '+', 'order' => 4],
        ];

        foreach ($stats as $stat) {
            CompanyStatistic::firstOrCreate(
                ['label' => $stat['label']],
                [
                    'number' => $stat['number'],
                    'suffix' => $stat['suffix'],
                    'order' => $stat['order'],
                ]
            );
        }
    }
}
