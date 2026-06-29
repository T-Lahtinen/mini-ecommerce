<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::query()
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                return [
                    'productNo' => $product->product_no,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'salePrice' => $product->sale_price,
                    'discountPercentage' => $product->discountPercentage(),
                ];
            });
    }
}
