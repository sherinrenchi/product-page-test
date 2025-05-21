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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'slug' => 'required|string|unique:products',
            'price' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $product = Product::create($validated);

        // Optionally handle images & discount here if provided

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'slug' => 'sometimes|string|unique:products,slug,' . $product->id,
            'price' => 'sometimes|integer',
            'active' => 'sometimes|boolean',
            'images' => 'sometimes|array',
            'images.*' => 'required_with:images|string',
            'discount.type' => 'nullable|in:percent,amount',
            'discount.amount' => 'nullable|integer'
        ]);

        $product->update($validated);

        if (isset($validated['images'])) {
            $product->images()->delete(); // remove old images
            foreach ($validated['images'] as $path) {
                $product->images()->create(['path' => $path]);
            }
        }

        if (isset($validated['discount'])) {
            $product->discount()->updateOrCreate([], [
                'type' => $validated['discount']['type'],
                'discount' => $validated['discount']['amount']
            ]);
        }

        return response()->json(['message' => 'Product updated successfully']);
    }
    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->images()->delete();
        $product->discount()->delete();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

}
