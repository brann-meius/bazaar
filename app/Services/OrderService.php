<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Orders\CalculateOrderContract;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Supports\Contexts\OrderProductContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CalculateOrderContract $calculateOrderHandler,
        private ProductRepository $productRepository,
        private OrderProductContext $productContext,
    ) {
        //
    }

    /**
     * @throws Throwable
     */
    public function create(User $user, Collection $productsFromRequest): Order
    {
        return DB::transaction(function () use ($user, $productsFromRequest) {
            $order = $this->orderRepository->createBy($user);
            // Get the required products with a lock
            $products = $this->productRepository->list($productsFromRequest->pluck('id')->toArray(), true);

            $this->includeToContext($products, $productsFromRequest);

            return $this->recalculate($order, ...$products);
        });
    }

    public function recalculate(Order $order, Product ...$products): Order
    {
        if ($this->productContext->isEmpty()) {
            $this->includeToContext(Collection::wrap($products));
        }

        $this->calculateOrderHandler->execute($order, ...$products);

        return $this->orderRepository->find($order->id, [
            'products',
        ]);
    }

    private function includeToContext(Collection $products, ?Collection $productsFromRequest = null): void
    {
        $products->each(function (Product $product) use ($productsFromRequest) {
            if (is_null($productsFromRequest)) {
                $this->productContext->add($product);

                return;
            }

            $this->productContext->add(
                $product,
                $productsFromRequest->firstWhere('id', $product->id)['count']
            );
        });
    }
}
