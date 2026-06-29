<?php

namespace App\Services;

use App\Data\CreateOrderData;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class OrderService
{
    public function create(CreateOrderData $data): Order
    {
        return DB::transaction(function () use ($data) {
            $productNos = collect($data->items)->pluck('productNo');

            $products = Product::query()
                ->whereIn('product_no', $productNos)
                ->get()
                ->keyBy('product_no');

            $missingProductNos = $productNos
                ->diff($products->keys())
                ->values();

            if ($missingProductNos->isNotEmpty()) {
                throw new InvalidArgumentException(
                    'Products not found: '.$missingProductNos->implode(', ')
                );
            }

            $totalPrice = collect($data->items)->sum(function ($item) use ($products) {
                $product = $products->get($item->productNo);
                $effectivePrice = $product->sale_price ?? $product->price;

                return $effectivePrice * $item->quantity;
            });

            $order = Order::create([
                'first_name' => $data->firstName,
                'last_name' => $data->lastName,
                'customer_email' => $data->customerEmail,
                'total_price' => $totalPrice,
            ]);

            foreach ($data->items as $item) {
                $product = $products->get($item->productNo);
                $effectivePrice = $product->sale_price ?? $product->price;
                $lineTotal = $effectivePrice * $item->quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $effectivePrice,
                    'line_total' => $lineTotal,
                ]);
            }

            Log::info('Order created', [
                'order_id' => $order->id,
                'total_price' => $order->total_price,
            ]);

            return $order->load('items.product');
        });
    }
}
