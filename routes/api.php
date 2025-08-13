<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserOrderController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::as('products.')
    ->prefix('products')
    ->group(function (): void {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::prefix('{product}')
            ->group(function (): void {
                Route::get('/', [ProductController::class, 'show'])->name('show');
            });
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::as('users.')
            ->prefix('users/{user}')
            ->group(function (): void {
                Route::as('orders.')
                    ->prefix('orders')
                    ->group(function (): void {
                        Route::get('/', [UserOrderController::class, 'index'])->name('index');
                        Route::post('/', [UserOrderController::class, 'store'])->name('store');
                    });
            });

        Route::as('orders.')
            ->prefix('orders')
            ->group(function (): void {
                Route::prefix('{order}')
                    ->group(function (): void {
                        Route::get('/', [OrderController::class, 'show'])->name('show');
                        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
                    });

                Route::as('products.')
                    ->prefix('{order}/products')
                    ->group(function (): void {
                        Route::get('/', [OrderProductController::class, 'index'])->name('index');
                        Route::post('{product}', [OrderProductController::class, 'store'])->name('store');
                        Route::delete('{product}', [OrderProductController::class, 'destroy'])->name('destroy');
                    });
            });

        Route::as('products.')
            ->prefix('products')
            ->group(function (): void {
                Route::post('/', [ProductController::class, 'store'])->name('store');
                Route::prefix('{product}')
                    ->group(function (): void {
                        Route::put('/', [ProductController::class, 'update'])->name('update');
                        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
                    });
            });
    });
