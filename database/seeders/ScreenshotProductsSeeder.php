<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ScreenshotProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'B3 MULTI ACTIVE TONER',
                'category' => 'toners',
                'price' => 17000,
                'discount_price' => 16000,
            ],
            [
                'name' => 'KOJIVIT ULTRA 30g',
                'category' => 'skincare',
                'price' => 14500,
                'discount_price' => 13500,
            ],
            [
                'name' => 'FAIDRA LONGLASTING DELUXE 2',
                'category' => 'Others',
                'price' => 7500,
                'discount_price' => 7000,
            ],
            [
                'name' => 'Naked wolf 41-47',
                'category' => 'mens-fashion',
                'price' => 26000,
                'discount_price' => 25000,
            ],
            [
                'name' => 'Dermatologist', // Assuming truncated name
                'category' => 'cleansers',
                'price' => 27000,
                'discount_price' => 25000,
            ],
            [
                'name' => 'Palmer - Cocoa Butter formula with vitamin E',
                'category' => 'moisturizers',
                'price' => 12000,
                'discount_price' => 10000,
            ],
            [
                'name' => 'Kojie san',
                'category' => 'skincare',
                'price' => 11500,
                'discount_price' => 10500,
            ],
            [
                'name' => 'Face Fact Ceramide',
                'category' => 'moisturizers',
                'price' => 7000,
                'discount_price' => 5500,
            ],
            [
                'name' => 'CeraVe Foaming Cleanser 8FL OZ/236 ml',
                'category' => 'moisturizers',
                'price' => 25000,
                'discount_price' => 24000,
            ],
            [
                'name' => 'Men polo',
                'category' => 'mens-fashion',
                'price' => 25000,
                'discount_price' => 23000,
            ],
            [
                'name' => 'Grey Mens plain Jeans',
                'category' => 'mens-fashion',
                'price' => 25000,
                'discount_price' => 20000,
            ],
            [
                'name' => 'Omega 3 Fish Oil',
                'category' => 'serums',
                'price' => 8500,
                'discount_price' => 7500,
            ],
            [
                'name' => 'Adidas sneakers 42 to 46(Size)',
                'category' => 'mens-fashion',
                'price' => 40000,
                'discount_price' => 28000,
            ],
            [
                'name' => 'Ladies shoe Size 38 to 41(Size)',
                'category' => 'womens-fashion',
                'price' => 22000,
                'discount_price' => 18000,
            ],
            [
                'name' => 'Givenchy Quality half shoe 43 to 46(Size)',
                'category' => 'mens-fashion',
                'price' => 40000,
                'discount_price' => 38000,
            ],
            [
                'name' => 'Boxed Versace Quality half shoe 43 to 46(Size)',
                'category' => 'mens-fashion',
                'price' => 40000,
                'discount_price' => 38000,
            ],
            [
                'name' => 'Nike Air Force 40-57(Size)',
                'category' => 'mens-fashion',
                'price' => 30000,
                'discount_price' => 23000,
            ],
        ];

        foreach ($products as $data) {
            // Find or create category
            $category = ProductCategory::firstOrCreate(
                ['slug' => Str::slug($data['category'])],
                [
                    'name' => ucfirst($data['category']),
                    'is_active' => true,
                    'description' => 'Category for ' . $data['category']
                ]
            );

            // Create product
            Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => 'Description for ' . $data['name'],
                'price' => $data['price'],
                'discount_price' => $data['discount_price'],
                'product_category_id' => $category->id,
                'stock_quantity' => 50, // Default stock
                'is_active' => true,
                'image' => json_encode(['placeholder.jpg']), // Placeholder image
                'tags' => ['seeded', $data['category']],
            ]);
        }
    }
}
