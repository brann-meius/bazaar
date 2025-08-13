<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Attributes\Database\LockForUpdate;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Extends Laravel's SubstituteBindings middleware to support transactional route model binding
 * with optional row-level locking for parameters annotated with the LockForUpdate attribute.
 */
class TransactionalSubstituteBindings extends SubstituteBindings
{
    /**
     * @throws Throwable
     */
    public function handle($request, Closure $next): Response
    {
        $route = $request->route();
        $parameters = $this->getParameters($route);

        if (empty($parameters)) {
            return parent::handle($request, $next);
        }

        DB::beginTransaction();

        parent::handle($request, fn (Request $request): Request => $request);

        foreach ($parameters as $parameter => $class) {
            $model = $route->parameter($parameter);
            if ($model instanceof Model) {
                $this->bindAndLockParameter($route, $model, $parameter);
            }
        }

        /** @var Response $response */
        $response = $next($request);

        if ($response->isClientError() || $response->isServerError()) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        return $response;
    }

    /**
     * Retrieves parameters annotated with the `LockForUpdate` attribute.
     *
     * @return array{string, class-string<Model>}
     */
    private function getParameters(?Route $route): array
    {
        try {
            $reflection = new ReflectionMethod(
                $route->getControllerClass(),
                $route->getActionMethod()
            );
        } catch (ReflectionException) {
            return [];
        }

        $parameters = [];
        foreach ($reflection->getParameters() as $parameter) {
            $attributes = $parameter->getAttributes(LockForUpdate::class);
            if (empty($attributes)) {
                continue;
            }

            if (($type = $parameter->getType()) && is_subclass_of($type->getName(), Model::class)) {
                $parameters[$parameter->getName()] = $type->getName();
            }
        }

        return $parameters;
    }

    /**
     * Binds the given model to the specified route parameter with a row-level lock.
     */
    private function bindAndLockParameter(Route $route, Model $model, string $parameter): void
    {
        $lock = $model::query()
            ->whereKey($model)
            ->lockForUpdate()
            ->firstOrFail();

        $route->setParameter($parameter, $lock);
    }
}
