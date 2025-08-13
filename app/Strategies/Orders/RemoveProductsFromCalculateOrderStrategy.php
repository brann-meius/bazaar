<?php

declare(strict_types=1);

namespace App\Strategies\Orders;

use App\Dto\OrderDto;
use App\Dto\ProductDto;
use App\Exceptions\Products\CannotRemoveProductException;
use App\Exceptions\Products\ProductNotInOrderException;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Throwable;

class RemoveProductsFromCalculateOrderStrategy extends CalculateOrderStrategy
{
    /**
     * @throws Throwable
     */
    protected function calculate(Order $order, Product $product, OrderDto $orderDto): OrderDto
    {
        // Retrieving a product from an existing collection.
        $productWithPivot = $order->products->find($product);
        /** @var OrderProduct $pivot */
        $pivot = $productWithPivot->pivot;
        $quantity = $this->productContext->getRequiredQuantityFor($product);

        if ($quantity > $pivot->quantity) {
            throw new CannotRemoveProductException(
                "Not enough quantity of '[$product->id] $product->name' in the order."
            );
        }

        $this->realizeProducts($order, $product, $quantity, $pivot->quantity);

        return $orderDto->removeFromTotalAmount($this->productContext->calculateFor($product));
    }

    protected function register(Order $order, Product $product): void
    {
        throw new ProductNotInOrderException("Product '[$product->id] $product->name' is not in the order.");
    }

    /**
     * @throws Throwable
     */
    private function realizeProducts(Order $order, Product $product, int $quantity, int $orderProductQuantity): void
    {
        if ($quantity === $orderProductQuantity) {
            $this->orderProductRepository->detach($order, $product);
        } else {
            $this->orderProductRepository->decrement($order, $product, $quantity);
        }

        $this->productRepository->update(
            $product,
            ProductDto::from($product)
                ->setStockQuantity($product->stock_quantity + $quantity)
        );
    }
}
