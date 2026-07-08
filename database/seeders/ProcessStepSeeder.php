<?php

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['step_number' => 1, 'title' => 'التواصل والاستشارة', 'description' => 'نستمع لمتطلباتك ونقدم استشارة أولية مجانية.'],
            ['step_number' => 2, 'title' => 'المعاينة والتصميم', 'description' => 'معاينة الموقع وإعداد تصميم يناسب رؤيتك.'],
            ['step_number' => 3, 'title' => 'عرض السعر والتعاقد', 'description' => 'تقديم عرض سعر تفصيلي شفاف واضح.'],
            ['step_number' => 4, 'title' => 'التنفيذ', 'description' => 'تنفيذ المشروع بأعلى معايير الجودة والالتزام بالمواعيد.'],
            ['step_number' => 5, 'title' => 'التسليم والمتابعة', 'description' => 'تسليم المشروع مع ضمان شامل ومتابعة ما بعد التسليم.'],
        ];

        foreach ($steps as $step) {
            ProcessStep::firstOrCreate(
                ['step_number' => $step['step_number']],
                [
                    'title' => $step['title'],
                    'description' => $step['description'],
                    'order' => $step['step_number'],
                ]
            );
        }
    }
}
