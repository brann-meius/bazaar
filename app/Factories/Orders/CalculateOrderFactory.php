<?php

declare(strict_types=1);

namespace App\Factories\Orders;

use App\Contracts\Orders\CalculateOrderContract;
use App\Strategies\Orders\AddProductsToCalculateOrderStrategy;
use App\Strategies\Orders\RemoveProductsFromCalculateOrderStrategy;

class CalculateOrderFactory
{
    public function instance(string $method): CalculateOrderContract
    {
        $class = match ($method) {
            'DELETE' => RemoveProductsFromCalculateOrderStrategy::class,
            default => AddProductsToCalculateOrderStrategy::class,
        };

        return resolve($class);
    }
}
