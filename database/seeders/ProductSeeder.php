<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Define base products with variants
        $baseProducts = [
            // Nike Products
            [
                'brand' => 'Nike',
                'base_name' => 'Nike Sportswear Essential',
                'type' => 'lengan pendek',
                'selling_price' => 275000,
                'cost_price' => 180000,
                'description' => 'Classic Nike tee with embroidered swoosh. Premium cotton blend for ultimate comfort.',
                'colors' => ['Black', 'White', 'Grey'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_range' => [15, 30],
            ],
            [
                'brand' => 'Nike',
                'base_name' => 'Nike Dri-FIT Performance',
                'type' => 'lengan pendek',
                'selling_price' => 320000,
                'cost_price' => 210000,
                'description' => 'Moisture-wicking fabric keeps you dry and comfortable during workouts.',
                'colors' => ['White', 'Navy', 'Red'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [10, 25],
            ],
            [
                'brand' => 'Nike',
                'base_name' => 'Nike Tech Fleece',
                'type' => 'lengan panjang',
                'selling_price' => 890000,
                'cost_price' => 580000,
                'description' => 'Premium fleece with innovative design for warmth without weight.',
                'colors' => ['Grey', 'Black'],
                'sizes' => ['M', 'L', 'XL', '2XL'],
                'stock_range' => [5, 15],
            ],

            // Adidas Products
            [
                'brand' => 'Adidas',
                'base_name' => 'Adidas Originals Trefoil',
                'type' => 'lengan pendek',
                'selling_price' => 295000,
                'cost_price' => 195000,
                'description' => 'Iconic trefoil logo on soft cotton. Streetwear essential.',
                'colors' => ['Black', 'White', 'Navy'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_range' => [20, 35],
            ],
            [
                'brand' => 'Adidas',
                'base_name' => 'Adidas Performance Training',
                'type' => 'lengan pendek',
                'selling_price' => 310000,
                'cost_price' => 205000,
                'description' => 'Breathable athletic tee with moisture management.',
                'colors' => ['Red', 'Blue', 'Black'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [15, 25],
            ],
            [
                'brand' => 'Adidas',
                'base_name' => 'Adidas Essentials Hoodie',
                'type' => 'lengan panjang',
                'selling_price' => 750000,
                'cost_price' => 490000,
                'description' => 'Comfortable cotton-blend hoodie with kangaroo pocket.',
                'colors' => ['Black', 'Grey', 'Navy'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [10, 20],
            ],

            // Uniqlo Products
            [
                'brand' => 'Uniqlo',
                'base_name' => 'Uniqlo AIRism Cotton',
                'type' => 'lengan pendek',
                'selling_price' => 149000,
                'cost_price' => 95000,
                'description' => 'Innovative AIRism technology for smooth, cool comfort.',
                'colors' => ['White', 'Black', 'Grey', 'Navy'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_range' => [30, 50],
            ],
            [
                'brand' => 'Uniqlo',
                'base_name' => 'Uniqlo Graphic Streetwear',
                'type' => 'lengan pendek',
                'selling_price' => 179000,
                'cost_price' => 115000,
                'description' => 'Bold graphic print on premium cotton. Limited edition design.',
                'colors' => ['Black', 'White'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [20, 35],
            ],
            [
                'brand' => 'Uniqlo',
                'base_name' => 'Uniqlo Oversized',
                'type' => 'lengan pendek',
                'selling_price' => 159000,
                'cost_price' => 100000,
                'description' => 'Relaxed oversized fit. Perfect for layering.',
                'colors' => ['Grey', 'Beige', 'Black'],
                'sizes' => ['L', 'XL', '2XL'],
                'stock_range' => [15, 30],
            ],

            // H&M Products
            [
                'brand' => 'H&M',
                'base_name' => 'H&M Regular Fit Cotton',
                'type' => 'lengan pendek',
                'selling_price' => 129000,
                'cost_price' => 80000,
                'description' => 'Basic crew neck tee in soft cotton jersey.',
                'colors' => ['White', 'Black', 'Grey', 'Navy'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_range' => [40, 60],
            ],
            [
                'brand' => 'H&M',
                'base_name' => 'H&M Printed',
                'type' => 'lengan pendek',
                'selling_price' => 149000,
                'cost_price' => 95000,
                'description' => 'Trendy print design. Regular fit with ribbed crew neck.',
                'colors' => ['Black', 'White', 'Red'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [25, 40],
            ],
            [
                'brand' => 'H&M',
                'base_name' => 'H&M Relaxed Fit Hoodie',
                'type' => 'lengan panjang',
                'selling_price' => 399000,
                'cost_price' => 260000,
                'description' => 'Soft cotton blend with hood and kangaroo pocket.',
                'colors' => ['Grey', 'Black'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_range' => [15, 25],
            ],

            // Premium Items
            [
                'brand' => 'Nike',
                'base_name' => 'Nike x OFF-WHITE Collaboration',
                'type' => 'lengan pendek',
                'selling_price' => 1250000,
                'cost_price' => 850000,
                'description' => 'Limited edition collaboration. Collectors item with unique design.',
                'colors' => ['White', 'Black'],
                'sizes' => ['M', 'L'],
                'stock_range' => [2, 5],
            ],
            [
                'brand' => 'Adidas',
                'base_name' => 'Adidas Yeezy Essentials',
                'type' => 'lengan pendek',
                'selling_price' => 980000,
                'cost_price' => 650000,
                'description' => 'Yeezy line premium oversized tee with unique colorway.',
                'colors' => ['Beige', 'Black'],
                'sizes' => ['L', 'XL'],
                'stock_range' => [3, 8],
            ],
        ];

        $productCount = 0;

        foreach ($baseProducts as $base) {
            foreach ($base['colors'] as $color) {
                foreach ($base['sizes'] as $size) {
                    $stock = rand($base['stock_range'][0], $base['stock_range'][1]);
                    
                    // Randomly set some variants to out of stock (10% chance)
                    if (rand(1, 10) === 1) {
                        $stock = 0;
                    }

                    Product::create([
                        'brand' => $base['brand'],
                        'type' => $base['type'],
                        'color' => $color,
                        'size' => $size,
                        'selling_price' => $base['selling_price'],
                        'cost_price' => $base['cost_price'],
                        'stock' => $stock,
                        'description' => $base['description'],
                        'is_active' => true,
                    ]);

                    $productCount++;
                }
            }
        }

        $this->command->info("Created {$productCount} product variants successfully!");
    }
}
