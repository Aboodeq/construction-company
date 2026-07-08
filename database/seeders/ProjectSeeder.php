<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // 6 تصنيفات
        ProjectCategory::factory()->count(6)->create();

        // 12 مشروع
        Project::factory()->count(12)->create()->each(function (Project $project) {
            // 5 صور معرض عام
            ProjectImage::factory()->count(5)->create([
                'project_id' => $project->id,
            ]);

            // 3 صور "قبل"
            ProjectImage::factory()->before()->count(3)->create([
                'project_id' => $project->id,
            ]);

            // 3 صور "بعد"
            ProjectImage::factory()->after()->count(3)->create([
                'project_id' => $project->id,
            ]);

            // ربط المشروع بـ 1-3 خدمات عشوائية
            $randomServices = Service::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $project->services()->sync($randomServices);
        });
    }
}
