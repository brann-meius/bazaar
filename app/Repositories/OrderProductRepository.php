<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderProductRepository
{
    public function increment(Order $order, Product $product, int $quantity): int
    {
        return OrderProduct::query()
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->increment('quantity', $quantity);
    }

    public function decrement(Order $order, Product $product, int $quantity): int
    {
        return OrderProduct::query()
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->decrement('quantity', $quantity);
    }

    public function attach(Order $order, Product $product): void
    {
        $order->products()->attach($product);
    }

    public function detach(Order $order, Product $product): int
    {
        return $order->products()->detach($product);
    }
}
