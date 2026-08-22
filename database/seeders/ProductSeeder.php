<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{
    Category,
    Product,
    ProductImage,
    ProductOption,
    ProductOptionValue,
    ProductVariant
};

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 1. CATEGORIES
        // =========================
        $categories = collect([
            'Ice Cream',
            'Drinks',
            'Snacks',
        ])->map(fn ($name) => Category::firstOrCreate([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        // =========================
        // 2. PRODUCT DATA
        // =========================
        $productsData = [
            [
                'name' => 'Classic Ice Cream',
                'category' => 'Ice Cream',
                'options' => [
                    'Flavor' => ['Vanilla', 'Chocolate', 'Strawberry'],
                    'Size' => ['100ml', '500ml'],
                ],
            ],
            [
                'name' => 'Fresh Juice',
                'category' => 'Drinks',
                'options' => [
                    'Flavor' => ['Mango', 'Apple', 'Orange'],
                    'Size' => ['250ml', '500ml'],
                ],
            ],
            [
                'name' => 'Crispy Chips',
                'category' => 'Snacks',
                'options' => [
                    'Flavor' => ['Salted', 'BBQ', 'Cheese'],
                    'Size' => ['Small', 'Large'],
                ],
            ],
        ];

        // =========================
        // 3. LOOP PRODUCTS
        // =========================
        foreach ($productsData as $pData) {

            $category = $categories->firstWhere('name', $pData['category']);

            // CREATE PRODUCT
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $pData['name'],
                'slug' => Str::slug($pData['name']),
                'short_description' => 'Sample product for ' . $pData['name'],
                'description' => 'Detailed description for ' . $pData['name'],
                'is_featured' => rand(0, 1),
                'is_active' => true,
            ]);

            // =========================
            // 4. IMAGES
            // =========================
            for ($i = 1; $i <= 3; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "products/{$product->id}/image{$i}.jpg",
                ]);
            }

            // =========================
            // 5. OPTIONS + VALUES
            // =========================
            $optionValueMap = [];

            foreach ($pData['options'] as $optionName => $values) {

                $option = ProductOption::create([
                    'product_id' => $product->id,
                    'name' => $optionName,
                ]);

                foreach ($values as $value) {

                    $optionValue = ProductOptionValue::create([
                        'product_option_id' => $option->id,
                        'value' => $value,
                    ]);

                    $optionValueMap[$optionName][] = $optionValue;
                }
            }

            // =========================
            // 6. GENERATE VARIANTS
            // =========================
            $this->generateVariants($product, $optionValueMap);
        }
    }

    private function generateVariants($product, $optionValueMap)
    {
        $combinations = $this->cartesian($optionValueMap);

        foreach ($combinations as $combo) {

            $skuParts = [];
            $price = $product->base_price;

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => 'SKU-' . strtoupper(Str::random(6)),
                'price' => $price + rand(0, 200) / 100,
                'stock' => rand(5, 50),
            ]);

            foreach ($combo as $optionValue) {

                $variant->optionValues()->attach($optionValue->id);

                $skuParts[] = strtoupper(substr($optionValue->value, 0, 3));
            }

            $variant->sku = 'SKU-' . implode('-', $skuParts) . '-' . $variant->id;
            $variant->save();
        }
    }

    private function cartesian($input)
    {
        $result = [[]];

        foreach ($input as $values) {
            $append = [];

            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[] = $item;
                    $append[] = $product;
                    array_pop($product);
                }
            }

            $result = $append;
        }

        return $result;
    }
}