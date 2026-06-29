<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_no',
        'name',
        'description',
        'price',
        'sale_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function discountPercentage(): ?int
    {
        if ($this->sale_price === null || (float) $this->price <= 0) {
            return null;
        }

        return (int) round(
            (((float) $this->price - (float) $this->sale_price) / (float) $this->price) * 100
        );
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
