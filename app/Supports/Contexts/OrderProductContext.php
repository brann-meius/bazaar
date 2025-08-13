<?php

declare(strict_types=1);

namespace App\Supports\Contexts;

use App\Models\Product;

class OrderProductContext
{
    private array $products = [];

    public function add(Product $product, int $count = 1): self
    {
        if (! array_key_exists($product->id, $this->products)) {
            $this->products[$product->id] = 0;
        }

        $this->products[$product->id] += $count;

        return $this;
    }

    public function getRequiredQuantityFor(Product $product)
    {
        return $this->products[$product->id] ?? 0;
    }

    public function calculateFor(Product $product): float
    {
        if (! array_key_exists($product->id, $this->products)) {
            return 0.00;
        }

        return $product->price * $this->products[$product->id];
    }

    public function isEmpty(): bool
    {
        return empty($this->products);
    }
}
