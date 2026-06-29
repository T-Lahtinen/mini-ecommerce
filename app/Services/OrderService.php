<?php

namespace App\Services;

use App\Data\OrderData;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(OrderData $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $products = Product::query()
                ->whereIn('product_no', collect($data->items)->pluck('productNo'))
                ->get()
                ->keyBy('product_no');

            $totalPrice = 0;

            foreach ($data->items as $item) {
                $product = $products->get($item->productNo);
                $unitPrice = (float) ($product->sale_price ?? $product->price);
                $totalPrice += $unitPrice * $item->quantity;
            }

            $order = Order::query()->create([
                'first_name' => $data->firstName,
                'last_name' => $data->lastName,
                'customer_email' => $data->customerEmail,
                'total_price' => $totalPrice,
            ]);

            foreach ($data->items as $item) {
                $product = $products->get($item->productNo);
                $unitPrice = (float) ($product->sale_price ?? $product->price);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $item->quantity,
                ]);
            }

            return $order->load('items');
        });
    }
}
