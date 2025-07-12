<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $products = [];
        for ($i = 1; $i <= 100; $i++) {
            $categoryId = $faker->numberBetween(1, 15);
            $price = $faker->numberBetween(1000, 100000);
            $discount = $faker->numberBetween(0, 10000);
            $salePrice = $price - $discount;
            $products[] = [
                'name' => $faker->words(2, true) . ' ' . $faker->randomElement(['Pro', 'Max', 'Plus', 'Lite', 'Ultra', 'Mini']),
                'description' => $faker->sentence(8),
                'price' => $price,
                'discount' => $discount,
                'sale_price' => $salePrice > 0 ? $salePrice : $price,
                'image' => null,
                'category_id' => $categoryId,
            ];
        }
        Product::insert($products);
    }
} 