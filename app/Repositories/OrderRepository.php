<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\OrderDto;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

class OrderRepository
{
    public function find(int $id, array $with = []): Order
    {
        return Order::query()
            ->with($with)
            ->find($id);
    }

    public function paginateBy(User $user): Paginator
    {
        return $user->orders()->simplePaginate();
    }

    public function createBy(User $user): Order
    {
        return $user->orders()->create();
    }

    public function update(Order $order, OrderDto $orderDto): bool
    {
        return $order->update($orderDto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function delete(Order $order): bool
    {
        return $order->deleteOrFail();
    }
}
