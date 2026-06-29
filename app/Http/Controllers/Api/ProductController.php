<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product): array => [
                'product_no' => $product->product_no,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'discount_percentage' => $product->discountPercentage(),
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }
}
