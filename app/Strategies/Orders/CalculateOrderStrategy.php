<?php

declare(strict_types=1);

namespace App\Strategies\Orders;

use App\Contracts\Orders\CalculateOrderContract;
use App\Dto\OrderDto;
use App\Exceptions\Products\ProductQuantityException;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Supports\Contexts\OrderProductContext;

abstract class CalculateOrderStrategy implements CalculateOrderContract
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository,
        protected OrderProductContext $productContext,
        protected OrderProductRepository $orderProductRepository,
    ) {
        //
    }

    /**
     * Recalculate for an existing product in the order.
     */
    abstract protected function calculate(Order $order, Product $product, OrderDto $orderDto): OrderDto;

    /**
     * Register a new product.
     */
    abstract protected function register(Order $order, Product $product): void;

    /**
     * @throws ProductQuantityException
     */
    public function execute(Order $order, Product ...$products): Order
    {
        $orderDto = OrderDto::from($order);

        foreach ($products as $product) {
            if (! $order->products->contains($product)) {
                $this->register($order, $product);
            }

            $this->calculate($order, $product, $orderDto);
        }

        $this->orderRepository->update($order, $orderDto);

        return $order;
    }
}
