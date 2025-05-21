<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        // Fetch product with images and discount
        $product = Product::with(['images', 'discount'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Calculate discounted price if discount exists
        $discountAmount = 0;
        $discountData = null;

        if ($product->discount) {
            if ($product->discount->type === 'percent') {
                $discountAmount = ($product->price * $product->discount->discount) / 100;
            } elseif ($product->discount->type === 'amount') {
                $discountAmount = $product->discount->discount;
            }

            $discountData = [
                'type' => $product->discount->type,
                'amount' => $product->discount->discount,
            ];
        }

        $discountedPrice = max($product->price - $discountAmount, 0);

        // Prepare images array (only paths)
        $images = $product->images->pluck('path')->toArray();

        return response()->json([
            'id' => (string) $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'slug' => $product->slug,
            'price' => [
                'full' => $product->price,
                'discounted' => $discountedPrice,
            ],
            'discount' => $discountData,
            'images' => $images,
        ]);
    }
}
