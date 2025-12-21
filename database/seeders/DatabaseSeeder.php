<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@best4uarena.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+2348089228381',
                'address' => 'Best4UArena HQ'
            ]
        );

        // Create Regular Users
        $user1 = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '+1987654321',
                'address' => '456 User Avenue, User City'
            ]
        );

        // Create Product Categories
        $categories = [
            ['name' => 'Skincare', 'slug' => 'skincare', 'description' => 'Premium skincare products', 'is_active' => true, 'image' => 'skincare.jpg'],
            ['name' => 'Mens Fashion', 'slug' => 'mens-fashion', 'description' => 'Latest trends in men\'s fashion', 'is_active' => true, 'image' => 'mens-fashion.jpg'],
            ['name' => 'Womens Fashion', 'slug' => 'womens-fashion', 'description' => 'Elegant and stylish women\'s fashion', 'is_active' => true, 'image' => 'womens-fashion.jpg'],
            ['name' => 'Others', 'slug' => 'others', 'description' => 'Other essential items', 'is_active' => true, 'image' => 'others.jpg'],
        ];

        $categoryMap = [];
        foreach ($categories as $categoryData) {
            $category = ProductCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
            $categoryMap[$categoryData['slug']] = $category->id;
        }

        // Create Products
        $products = [
            // Screenshot 1
            [
                'name' => 'GENIE COLLECTION NATURAL SPRAY 25ml',
                'slug' => 'genie-collection-natural-spray-25ml',
                'category_slug' => 'skincare',
                'price' => 6000,
                'discount_price' => 5000,
            ],
            [
                'name' => 'NAKED WOLF BLACK',
                'slug' => 'naked-wolf-black',
                'category_slug' => 'mens-fashion',
                'price' => 32000,
                'discount_price' => 30000,
            ],
            [
                'name' => '212 MEN LOVALI 100ml',
                'slug' => '212-men-lovali-100ml',
                'category_slug' => 'skincare',
                'price' => 7000,
                'discount_price' => 6000,
            ],
            [
                'name' => '24K ROUGE',
                'slug' => '24k-rouge',
                'category_slug' => 'others',
                'price' => 8000,
                'discount_price' => 7500,
            ],
            // Screenshot 2
            [
                'name' => 'AL FARIS PERFUME',
                'slug' => 'al-faris-perfume',
                'category_slug' => 'skincare',
                'price' => 8500,
                'discount_price' => 7500,
            ],
            [
                'name' => 'GK MEN FOR MEN PERFUME',
                'slug' => 'gk-men-for-men-perfume',
                'category_slug' => 'skincare',
                'price' => 16500,
                'discount_price' => 15500,
            ],
            [
                'name' => 'HUG PERFUME',
                'slug' => 'hug-perfume',
                'category_slug' => 'skincare',
                'price' => 6000,
                'discount_price' => 5000,
            ],
            [
                'name' => 'GIORGIO BLACK AND PINK PERFUME',
                'slug' => 'giorgio-black-and-pink-perfume',
                'category_slug' => 'skincare',
                'price' => 7000,
                'discount_price' => 6000,
            ],
            // Screenshot 3
            [
                'name' => 'ONIRO NATURAL SPRAY PERFUME',
                'slug' => 'oniro-natural-spray-perfume',
                'category_slug' => 'skincare',
                'price' => 19500,
                'discount_price' => 18500,
            ],
            [
                'name' => 'RIGHS LONDON FRAGRANCE FOR KIDS',
                'slug' => 'righs-london-fragrance-for-kids',
                'category_slug' => 'skincare',
                'price' => 17500,
                'discount_price' => 16500,
            ],
            [
                'name' => 'RIGHS LONDON FRAGRANCE',
                'slug' => 'righs-london-fragrance',
                'category_slug' => 'skincare',
                'price' => 3500,
                'discount_price' => 2500,
            ],
            [
                'name' => 'HARTZ FRAGRANCE',
                'slug' => 'hartz-fragrance',
                'category_slug' => 'skincare',
                'price' => 5500,
                'discount_price' => 4500,
            ],
            // Screenshot 4
            [
                'name' => 'KHAMRAH LATAFA FRAGRANCE',
                'slug' => 'khamrah-latafa-fragrance',
                'category_slug' => 'others',
                'price' => 19000,
                'discount_price' => 18000,
            ],
            [
                'name' => 'ARO-FAC SUGER NATURAL SPRAY 100ml',
                'slug' => 'aro-fac-suger-natural-spray-100ml',
                'category_slug' => 'skincare',
                'price' => 36000,
                'discount_price' => 35000,
            ],
            [
                'name' => 'VANNILA SKY PERFUME 100ml',
                'slug' => 'vannila-sky-perfume-100ml',
                'category_slug' => 'skincare',
                'price' => 11000,
                'discount_price' => 10000,
            ],
            [
                'name' => 'CHOCO MUSK NATURAL PERFUME',
                'slug' => 'choco-musk-natural-perfume',
                'category_slug' => 'skincare',
                'price' => 7500,
                'discount_price' => 6000,
            ],
             // Screenshot 5
            [
                'name' => 'DEAR BODY FRAGRANCE MIST',
                'slug' => 'dear-body-fragrance-mist',
                'category_slug' => 'skincare',
                'price' => 7000,
                'discount_price' => 6000,
            ],
            [
                'name' => 'BLACK LEATHER MEN PERFUME',
                'slug' => 'black-leather-men-perfume',
                'category_slug' => 'skincare',
                'price' => 18500,
                'discount_price' => 18000,
            ],
            [
                'name' => 'VINTAGE RADIO PERFUME',
                'slug' => 'vintage-radio-perfume',
                'category_slug' => 'skincare',
                'price' => 46000,
                'discount_price' => 45000,
            ],
            [
                'name' => 'SCENT OF VENUS REED DIFFUSER',
                'slug' => 'scent-of-venus-reed-diffuser',
                'category_slug' => 'skincare',
                'price' => 7000,
                'discount_price' => 6500,
            ],
        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category_slug'];
            unset($productData['category_slug']);
            
            $productData['product_category_id'] = $categoryMap[$categorySlug] ?? 1;
            $productData['description'] = $productData['name'] . ' - High quality product from Best4UArena.';
            $productData['stock_quantity'] = 50;
            $productData['is_active'] = true;
            $productData['tags'] = json_encode([$categorySlug, 'fashion', 'beauty']);
            $productData['image'] = $productData['slug'] . '.jpg';
            
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
