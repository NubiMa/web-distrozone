<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Define parent products
        $products = [
            // Nike Products
            [
                'name' => 'Nike Sportswear Essential',
                'brand' => 'Nike',
                'type' => 'lengan pendek',
                'base_price' => 275000,
                'description' => 'Classic Nike tee with embroidered swoosh. Premium cotton blend for ultimate comfort.',
                'variants' => [
                    ['color' => 'Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [15, 25, 30, 20]],
                    ['color' => 'White', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [20, 30, 25, 15]],
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL'], 'stock' => [15, 20, 18]],
                ],
            ],
            [
                'name' => 'Nike Dri-FIT Performance',
                'brand' => 'Nike',
                'type' => 'lengan pendek',
                'base_price' => 320000,
                'description' => 'Moisture-wicking fabric keeps you dry and comfortable during workouts.',
                'variants' => [
                    ['color' => 'White', 'sizes' => ['M', 'L', 'XL'], 'stock' => [12, 15, 10]],
                    ['color' => 'Navy', 'sizes' => ['M', 'L', 'XL'], 'stock' => [10, 12, 8]],
                    ['color' => 'Red', 'sizes' => ['M', 'L'], 'stock' => [8, 10]],
                ],
            ],
            [
                'name' => 'Nike Tech Fleece Hoodie',
                'brand' => 'Nike',
                'type' => 'lengan panjang',
                'base_price' => 890000,
                'description' => 'Premium fleece with innovative design for warmth without weight.',
                'variants' => [
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL', '2XL'], 'stock' => [8, 10, 12, 5]],
                    ['color' => 'Black', 'sizes' => ['M', 'L', 'XL'], 'stock' => [10, 12, 8]],
                ],
            ],

            // Adidas Products
            [
                'name' => 'Adidas Originals Trefoil Tee',
                'brand' => 'Adidas',
                'type' => 'lengan pendek',
                'base_price' => 295000,
                'description' => 'Iconic trefoil logo on soft cotton. Streetwear essential.',
                'variants' => [
                    ['color' => 'Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [20, 25, 30, 15]],
                    ['color' => 'White', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [18, 22, 20, 12]],
                    ['color' => 'Navy', 'sizes' => ['M', 'L', 'XL'], 'stock' => [15, 18, 10]],
                ],
            ],
            [
                'name' => 'Adidas Performance Training Tee',
                'brand' => 'Adidas',
                'type' => 'lengan pendek',
                'base_price' => 310000,
                'description' => 'Breathable athletic tee with moisture management.',
                'variants' => [
                    ['color' => 'Red', 'sizes' => ['M', 'L', 'XL'], 'stock' => [12, 15, 10]],
                    ['color' => 'Blue', 'sizes' => ['M', 'L', 'XL'], 'stock' => [10, 12, 8]],
                    ['color' => 'Black', 'sizes' => ['M', 'L'], 'stock' => [15, 12]],
                ],
            ],
            [
                'name' => 'Adidas Essentials Hoodie',
                'brand' => 'Adidas',
                'type' => 'lengan panjang',
                'base_price' => 750000,
                'description' => 'Comfortable cotton-blend hoodie with kangaroo pocket.',
                'variants' => [
                    ['color' => 'Black', 'sizes' => ['M', 'L', 'XL'], 'stock' => [10, 12, 8]],
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL'], 'stock' => [12, 10, 6]],
                    ['color' => 'Navy', 'sizes' => ['L', 'XL'], 'stock' => [8, 5]],
                ],
            ],

            // Uniqlo Products
            [
                'name' => 'Uniqlo AIRism Cotton Tee',
                'brand' => 'Uniqlo',
                'type' => 'lengan pendek',
                'base_price' => 149000,
                'description' => 'Innovative AIRism technology for smooth, cool comfort.',
                'variants' => [
                    ['color' => 'White', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [30, 40, 35, 25]],
                    ['color' => 'Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [28, 38, 32, 22]],
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL'], 'stock' => [25, 30, 20]],
                    ['color' => 'Navy', 'sizes' => ['M', 'L', 'XL'], 'stock' => [20, 25, 15]],
                ],
            ],
            [
                'name' => 'Uniqlo Graphic Streetwear Tee',
                'brand' => 'Uniqlo',
                'type' => 'lengan pendek',
                'base_price' => 179000,
                'description' => 'Bold graphic print on premium cotton. Limited edition design.',
                'variants' => [
                    ['color' => 'Black', 'sizes' => ['M', 'L', 'XL'], 'stock' => [18, 22, 15]],
                    ['color' => 'White', 'sizes' => ['M', 'L', 'XL'], 'stock' => [20, 25, 18]],
                ],
            ],
            [
                'name' => 'Uniqlo Oversized Tee',
                'brand' => 'Uniqlo',
                'type' => 'lengan pendek',
                'base_price' => 159000,
                'description' => 'Relaxed oversized fit. Perfect for layering.',
                'variants' => [
                    ['color' => 'Grey', 'sizes' => ['L', 'XL', '2XL'], 'stock' => [15, 20, 12]],
                    ['color' => 'Beige', 'sizes' => ['L', 'XL', '2XL'], 'stock' => [12, 18, 10]],
                    ['color' => 'Black', 'sizes' => ['L', 'XL'], 'stock' => [18, 15]],
                ],
            ],

            // H&M Products
            [
                'name' => 'H&M Regular Fit Cotton Tee',
                'brand' => 'H&M',
                'type' => 'lengan pendek',
                'base_price' => 129000,
                'description' => 'Basic crew neck tee in soft cotton jersey.',
                'variants' => [
                    ['color' => 'White', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [40, 50, 45, 30]],
                    ['color' => 'Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'stock' => [38, 48, 42, 28]],
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL'], 'stock' => [30, 35, 25]],
                    ['color' => 'Navy', 'sizes' => ['M', 'L', 'XL'], 'stock' => [25, 30, 20]],
                ],
            ],
            [
                'name' => 'H&M Printed Tee',
                'brand' => 'H&M',
                'type' => 'lengan pendek',
                'base_price' => 149000,
                'description' => 'Trendy print design. Regular fit with ribbed crew neck.',
                'variants' => [
                    ['color' => 'Black', 'sizes' => ['M', 'L', 'XL'], 'stock' => [20, 25, 15]],
                    ['color' => 'White', 'sizes' => ['M', 'L', 'XL'], 'stock' => [18, 22, 12]],
                    ['color' => 'Red', 'sizes' => ['M', 'L'], 'stock' => [15, 18]],
                ],
            ],
            [
                'name' => 'H&M Relaxed Fit Hoodie',
                'brand' => 'H&M',
                'type' => 'lengan panjang',
                'base_price' => 399000,
                'description' => 'Soft cotton blend with hood and kangaroo pocket.',
                'variants' => [
                    ['color' => 'Grey', 'sizes' => ['M', 'L', 'XL'], 'stock' => [15, 18, 10]],
                    ['color' => 'Black', 'sizes' => ['M', 'L', 'XL'], 'stock' => [12, 15, 8]],
                ],
            ],

            // Premium Items
            [
                'name' => 'Nike x OFF-WHITE Collaboration Tee',
                'brand' => 'Nike',
                'type' => 'lengan pendek',
                'base_price' => 1250000,
                'description' => 'Limited edition collaboration. Collectors item with unique design.',
                'variants' => [
                    ['color' => 'White', 'sizes' => ['M', 'L'], 'stock' => [3, 2]],
                    ['color' => 'Black', 'sizes' => ['M', 'L'], 'stock' => [2, 3]],
                ],
            ],
            [
                'name' => 'Adidas Yeezy Essentials Tee',
                'brand' => 'Adidas',
                'type' => 'lengan pendek',
                'base_price' => 980000,
                'description' => 'Yeezy line premium oversized tee with unique colorway.',
                'variants' => [
                    ['color' => 'Beige', 'sizes' => ['L', 'XL'], 'stock' => [5, 3]],
                    ['color' => 'Black', 'sizes' => ['L', 'XL'], 'stock' => [4, 4]],
                ],
            ],
        ];

        $totalProducts = 0;
        $totalVariants = 0;

        foreach ($products as $productData) {
            // Create parent product
            $product = Product::create([
                'name' => $productData['name'],
                'brand' => $productData['brand'],
                'type' => $productData['type'],
                'base_price' => $productData['base_price'],
                'description' => $productData['description'],
                'is_active' => true,
            ]);

            $totalProducts++;

            // Create variants
            foreach ($productData['variants'] as $variantGroup) {
                foreach ($variantGroup['sizes'] as $index => $size) {
                    $stock = $variantGroup['stock'][$index] ?? 0;

                    // Randomly set some variants to out of stock (5% chance)
                    if (rand(1, 20) === 1) {
                        $stock = 0;
                    }

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $variantGroup['color'],
                        'size' => $size,
                        'stock' => $stock,
                        'price' => $productData['base_price'],
                        'cost_price' => $productData['base_price'] * 0.65, // 35% margin
                        'is_active' => true,
                    ]);

                    $totalVariants++;
                }
            }
        }

        $this->command->info("Created {$totalProducts} products with {$totalVariants} variants successfully!");
    }
}
