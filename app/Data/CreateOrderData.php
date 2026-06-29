<?php

namespace App\Data;

class CreateOrderData
{
    /**
     * @param OrderItemData[] $items
     */
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $customerEmail,
        public readonly array $items,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            customerEmail: $data['customer_email'],
            items: array_map(
                fn (array $item) => new OrderItemData(
                    productNo: $item['product_no'],
                    quantity: $item['quantity'],
                ),
                $data['items']
            ),
        );
    }
}
