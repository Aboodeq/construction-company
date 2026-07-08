<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceFaq;
use App\Models\ServiceImage;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 4 تصنيفات
        ServiceCategory::factory()->count(4)->create();

        // 10 خدمات، كل واحدة مرتبطة بتصنيف عشوائي
        Service::factory()->count(10)->create()->each(function (Service $service) {
            // 3 صور معرض لكل خدمة
            ServiceImage::factory()->count(3)->create([
                'service_id' => $service->id,
            ]);

            // 3 أسئلة شائعة لكل خدمة
            ServiceFaq::factory()->count(3)->create([
                'service_id' => $service->id,
            ]);
        });
    }
}
