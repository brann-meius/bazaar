<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\ProductDto;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class ProductRepository
{
    public function list(array $ids, bool $lock = false): Collection
    {
        return Product::query()
            ->whereIn('id', $ids)
            ->when($lock, fn (Builder $builder): Builder => $builder->lockForUpdate())
            ->get();
    }

    public function paginate(): Paginator
    {
        return Product::query()
            ->simplePaginate();
    }

    public function paginateBy(Order $order): Paginator
    {
        return $order->products()
            ->simplePaginate();
    }

    public function create(ProductDto $dto): Product
    {
        return Product::query()->create($dto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function update(Product $product, ProductDto $dto): bool
    {
        return $product->updateOrFail($dto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function delete(Product $product): bool
    {
        return $product->deleteOrFail();
    }
}
