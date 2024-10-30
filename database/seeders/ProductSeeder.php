<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Basic Support',
            'No Ads',
            'Forums',
            'Analytics',
            'Full Content Access',
            'Priority Support',
            'Exclusive Content',
            'Monthly Webinars',
            'Unlimited Access',
            'Unlimited Storage',
            'VIP Services',
        ];

        foreach ($products as $product) {

            Product::factory([
                'name' => $product,
            ])->create();
        }
    }
}
