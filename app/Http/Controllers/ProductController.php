<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\Database\LockForUpdate;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
        //
    }

    public function index(): Response
    {
        return ProductResource::collection(
            $this->productRepository->paginate()
        )->response();
    }

    public function store(StoreRequest $request): Response
    {
        try {
            $product = $this->productRepository->create($request->toDto());
        } catch (QueryException) {
            return response()->json([
                'message' => 'Failed to create the product',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ProductResource::make($product)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Product $product): Response
    {
        return ProductResource::make($product)->response();
    }

    public function update(
        UpdateRequest $request,
        #[LockForUpdate] Product $product
    ): Response {
        try {
            $this->productRepository->update($product, $request->toDto());
        } catch (Throwable) {
            return response()->json([
                'message' => 'Unable to update the product.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ProductResource::make($product)->response();
    }

    public function destroy(
        #[LockForUpdate] Product $product
    ): Response {
        try {
            $this->productRepository->delete($product);
        } catch (Throwable) {
            return response()->json([
                'message' => 'Unable to delete the product.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent();
    }
}
