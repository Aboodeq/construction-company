<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'شقة سكنية', 'base_price_per_meter' => 800, 'order' => 1],
            ['name' => 'فيلا', 'base_price_per_meter' => 1200, 'order' => 2],
            ['name' => 'مكتب تجاري', 'base_price_per_meter' => 950, 'order' => 3],
            ['name' => 'محل تجاري', 'base_price_per_meter' => 1000, 'order' => 4],
        ];

        foreach ($types as $type) {
            PropertyType::firstOrCreate(
                ['name' => $type['name']],
                ['base_price_per_meter' => $type['base_price_per_meter'], 'order' => $type['order']]
            );
        }
    }
}
