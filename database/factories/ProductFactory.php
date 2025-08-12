<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0.01, 999999.99),
            'stock_quantity' => fake()->numberBetween(1, 5000),
        ];
    }
}
