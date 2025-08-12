<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\Database\LockForUpdate;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class OrderProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {
        //
    }

    public function index(Order $order): Response
    {
        return ProductResource::collection(
            $this->productRepository->paginateBy($order)
        )->response();
    }

    public function store(
        #[LockForUpdate] Order $order,
        #[LockForUpdate] Product $product
    ) {
        //todo:
    }

    public function destroy(
        #[LockForUpdate] Order $order,
        #[LockForUpdate] Product $product
    ) {
        //todo:
    }
}
