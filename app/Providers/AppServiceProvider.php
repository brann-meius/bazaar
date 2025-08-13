<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Orders\CalculateOrderContract;
use App\Factories\Orders\CalculateOrderFactory;
use App\Supports\Contexts\OrderProductContext;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        OrderProductContext::class,
    ];

    public function register(): void
    {
        $this->app->bind(CalculateOrderContract::class, function (Application $application): CalculateOrderContract {
            $route = $application->make('router')->current();

            return new CalculateOrderFactory()->instance(current($route->methods()));
        });
    }
}
