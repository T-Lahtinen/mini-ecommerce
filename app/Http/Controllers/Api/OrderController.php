<?php

namespace App\Http\Controllers\Api;

use App\Data\CreateOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $order = $orderService->create(
            CreateOrderData::fromArray($request->validated())
        );

       return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }
}
