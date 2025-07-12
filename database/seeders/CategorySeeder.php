<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => 'Mobiles', 'description' => 'Smartphones and accessories'],
            ['name' => 'Laptops', 'description' => 'Laptops and notebooks'],
            ['name' => 'Headphones', 'description' => 'Headphones and earphones'],
            ['name' => 'Bluetooth Devices', 'description' => 'Bluetooth speakers, headsets, etc.'],
            ['name' => 'Fans', 'description' => 'Electronic fans and coolers'],
            ['name' => 'Smart Watches', 'description' => 'Wearable smart devices'],
            ['name' => 'Tablets', 'description' => 'Tablets and iPads'],
            ['name' => 'Cameras', 'description' => 'Digital and DSLR cameras'],
            ['name' => 'Printers', 'description' => 'Printers and scanners'],
            ['name' => 'Monitors', 'description' => 'Computer monitors'],
            ['name' => 'Routers', 'description' => 'WiFi routers and networking'],
            ['name' => 'Power Banks', 'description' => 'Portable chargers'],
            ['name' => 'Speakers', 'description' => 'Home and portable speakers'],
            ['name' => 'Projectors', 'description' => 'Multimedia projectors'],
            ['name' => 'Accessories', 'description' => 'Cables, adapters, and more'],
        ]);
    }
} 