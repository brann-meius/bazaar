<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\Products\OutOfStockException;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserOrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly OrderService $orderService,
    ) {
        //
    }

    public function index(User $user): Response
    {
        return OrderResource::collection(
            $this->orderRepository->paginateBy($user)
        )->response();
    }

    public function store(StoreRequest $request, User $user)
    {
        try {
            $order = $this->orderService->create($user, $request->collect('products'));
        } catch (OutOfStockException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return response()->json([
                'error' => 'Unable to create order.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return OrderResource::make($order)->response();
    }
}
