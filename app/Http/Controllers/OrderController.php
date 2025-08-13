<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\Database\LockForUpdate;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
        //
    }

    public function show(Order $order): Response
    {
        return OrderResource::make($order->load([
            'user',
            'products',
        ]))->response();
    }

    public function destroy(
        #[LockForUpdate] Order $order
    ): Response {
        try {
            $this->orderRepository->delete($order);
        } catch (Throwable) {
            return response()->json([
                'message' => 'Unable to delete the order.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent();
    }
}
