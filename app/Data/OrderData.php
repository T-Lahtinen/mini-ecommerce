<?php

namespace App\Data;

class OrderData
{
    /**
     * @param array<int, OrderItemData> $items
     */
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $customerEmail,
        public readonly array $items,
    ) {
    }
}
