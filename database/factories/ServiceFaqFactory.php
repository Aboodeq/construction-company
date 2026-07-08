<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFaqFactory extends Factory
{
    public function definition(): array
    {
        $faqs = [
            ['q' => 'كم تستغرق مدة تنفيذ المشروع؟', 'a' => 'تختلف المدة حسب حجم المشروع وتعقيده، وسيتم تحديد جدول زمني دقيق بعد المعاينة.'],
            ['q' => 'هل تقدمون ضمانًا على الأعمال؟', 'a' => 'نعم، نقدم ضمانًا شاملًا على جميع أعمال التشطيب والتنفيذ.'],
            ['q' => 'هل يمكن تعديل التصميم أثناء التنفيذ؟', 'a' => 'نعم، نوفر مرونة في التعديل ضمن حدود معقولة بالتنسيق مع فريق المشروع.'],
            ['q' => 'ما هي طرق الدفع المتاحة؟', 'a' => 'نوفر خطط دفع مرنة على دفعات حسب مراحل إنجاز المشروع.'],
        ];

        $faq = fake()->randomElement($faqs);

        return [
            'question' => $faq['q'],
            'answer' => $faq['a'],
            'order' => fake()->numberBetween(0, 5),
        ];
    }
}
