<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Orders\StoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Repositories\OrderRepository;
use Symfony\Component\HttpFoundation\Response;

class UserOrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
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
        //todo:
    }
}
