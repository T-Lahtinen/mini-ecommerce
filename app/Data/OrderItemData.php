<?php

namespace App\Data;

class OrderItemData
{
    public function __construct(
        public readonly string $productNo,
        public readonly int $quantity,
    ) {
    }
}
