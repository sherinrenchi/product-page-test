<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\ProductImage;

class ProductFullDataSeeder extends Seeder
{
    public function run()
    {
        // Optional: clean up existing data if it already exists
        $existingProduct = \App\Models\Product::where('slug', 'fall-limited-edition-sneakers')->first();
        if ($existingProduct) {
            $existingProduct->images()->delete(); // delete related images
            $existingProduct->discount()->delete(); // delete related discount
            $existingProduct->delete(); // delete product itself
        }
        // Create product
        $product = Product::create([
            'name' => 'Fall Limited Edition Sneakers',
            'description' => "These low-profile sneakers are your perfect casual wear companion. Featuring a durable rubber outer sole, they'll withstand everything the weather can offer.",
            'slug' => 'fall-limited-edition-sneakers',
            'price' => 250,
            'active' => true,
        ]);

        // Add discount
        ProductDiscount::create([
            'product_id' => $product->id,
            'type' => 'percent',
            'discount' => 50,
        ]);

        // Add images
        ProductImage::insert([
            ['product_id' => $product->id, 'path' => 'image1.png'],
            ['product_id' => $product->id, 'path' => 'image2.png'],
            ['product_id' => $product->id, 'path' => 'image3.png'],
            ['product_id' => $product->id, 'path' => 'image4.png'],
        ]);
    }
}
