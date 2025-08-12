<?php

declare(strict_types=1);

namespace App\Dto;

class ProductDto extends Dto
{
    public function __construct(
        private(set) string $name,
        private(set) string $price,
        private(set) int $stockQuantity,
        private(set) ?string $description = null,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'stock_quantity' => $this->stockQuantity,
            'description' => $this->description,
        ];
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function setStockQuantity(int $stockQuantity): self
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;

        return $this;
    }
}
