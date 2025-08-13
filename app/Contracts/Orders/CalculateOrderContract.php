<?php

declare(strict_types=1);

namespace App\Contracts\Orders;

use App\Models\Order;
use App\Models\Product;

/**
 * Contract for calculate order strategies.
 *
 * Implementations of this interface should provide logic for calculating
 * or recalculating an order's totals based on different operations.
 */
interface CalculateOrderContract
{
    public function execute(Order $order, Product ...$product): Order;
}
