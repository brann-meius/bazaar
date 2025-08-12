<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

class OrderRepository
{
    public function paginateBy(User $user): Paginator
    {
        return $user->orders()->simplePaginate();
    }

    /**
     * @throws Throwable
     */
    public function delete(Order $order): bool
    {
        return $order->deleteOrFail();
    }
}
