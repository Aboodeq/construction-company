<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Foundational (ضرورية)
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SettingSeeder::class,
            PageSectionSeeder::class,
            PropertyTypeSeeder::class,
            FinishingLevelSeeder::class,
            HeroSlideSeeder::class,
            CompanyStatisticSeeder::class,
            ProcessStepSeeder::class,

            // Demo content (تجريبية)
            ServiceSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            TeamMemberSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
