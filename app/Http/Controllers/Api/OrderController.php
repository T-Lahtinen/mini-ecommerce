<?php

namespace App\Http\Controllers\Api;

use App\Data\OrderData;
use App\Data\OrderItemData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, OrderService $orderService): JsonResponse
    {
        $validated = $request->validated();

        $orderData = new OrderData(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            customerEmail: $validated['customer_email'],
            items: collect($validated['items'])
                ->map(fn (array $item): OrderItemData => new OrderItemData(
                    productNo: $item['product_no'],
                    quantity: $item['quantity'],
                ))
                ->all(),
        );

        $order = $orderService->create($orderData);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order' => [
                'id' => $order->id,
                'total_price' => $order->total_price,
            ],
        ], 201);
    }
}
