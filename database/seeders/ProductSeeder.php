<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->insert([
            [
                'product_no' => 'PROD-KEYBOARD-1001',
                'name' => 'Keyboard',
                'description' => 'A reliable mechanical keyboard for everyday work.',
                'price' => 59.99,
                'sale_price' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_no' => 'PROD-MOUSE-1002',
                'name' => 'Mouse',
                'description' => 'Compact Mouse: With a comfortable and contoured shape, this Localtech ambidextrous wireless mouse feels great in either right or left hand and is far superior to a touchpad.',
                'price' => 24.99,
                'sale_price' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_no' => 'PROD-MONITOR-1003',
                'name' => 'Monitor',
                'description' => "Stay in the action when playing games, watching videos, or working on creative projects. The 100Hz refresh rate reduces lag and motion blur so you don't miss a thing in fast-paced moments.",
                'price' => 199.99,
                'sale_price' => 159.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
