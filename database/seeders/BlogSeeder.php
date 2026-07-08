<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // 5 تصنيفات
        BlogCategory::factory()->count(5)->create();

        // 8 مقالات
        BlogPost::factory()->count(8)->create();
    }
}
