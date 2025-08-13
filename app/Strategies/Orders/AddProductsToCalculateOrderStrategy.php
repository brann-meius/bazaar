<?php

declare(strict_types=1);

namespace App\Strategies\Orders;

use App\Dto\OrderDto;
use App\Dto\ProductDto;
use App\Exceptions\Products\OutOfStockException;
use App\Models\Order;
use App\Models\Product;
use Throwable;

class AddProductsToCalculateOrderStrategy extends CalculateOrderStrategy
{
    /**
     * @throws Throwable
     */
    protected function calculate(Order $order, Product $product, OrderDto $orderDto): OrderDto
    {
        $quantity = $this->productContext->getRequiredQuantityFor($product);
        if ($product->stock_quantity < $quantity) {
            throw new OutOfStockException("Not enough stock for product '[$product->id] $product->name'.");
        }

        $this->reserveProducts($order, $product, $quantity);

        return $orderDto->addToTotalAmount($this->productContext->calculateFor($product));
    }

    protected function register(Order $order, Product $product): void
    {
        $this->orderProductRepository->attach($order, $product);
    }

    /**
     * @throws Throwable
     */
    private function reserveProducts(Order $order, Product $product, int $quantity): void
    {
        $this->orderProductRepository->increment($order, $product, $quantity);
        $this->productRepository->update(
            $product,
            ProductDto::from($product)
                ->setStockQuantity($product->stock_quantity - $quantity)
        );
    }
}
