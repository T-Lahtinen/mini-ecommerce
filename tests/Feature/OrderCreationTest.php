<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_requires_valid_items(): void
    {
        $response = $this->postJson('/api/orders', [
            'first_name' => 'John',
            'last_name' => 'Tester',
            'customer_email' => 'test@example.com',
            'items' => [
                [
                    'product_no' => 'DOES-NOT-EXIST',
                    'quantity' => 1,
                ],
            ],
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'items.0.product_no',
        ]);

        $this->assertDatabaseCount('orders', 0);
    }
}
