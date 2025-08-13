<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\Database\LockForUpdate;
use App\Exceptions\Products\CannotRemoveProductException;
use App\Exceptions\Products\OutOfStockException;
use App\Exceptions\Products\ProductNotInOrderException;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly OrderService $orderService,
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
    ): Response {
        try {
            $order = $this->orderService->recalculate($order, $product);
        } catch (OutOfStockException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return response()->json([
                'error' => 'Unable to add product to order.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return OrderResource::make($order)->response();
    }

    public function destroy(
        #[LockForUpdate] Order $order,
        #[LockForUpdate] Product $product
    ): Response {
        try {
            $order = $this->orderService->recalculate($order, $product);
        } catch (ProductNotInOrderException|CannotRemoveProductException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return response()->json([
                'error' => 'Unable to add product to order.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return OrderResource::make($order)->response();
    }
}
