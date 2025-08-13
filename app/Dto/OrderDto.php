<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\Order;

class OrderDto extends Dto
{
    public function __construct(
        private(set) string $totalAmount = '0.00',
    ) {
        //
    }

    public static function from(Order $order): OrderDto
    {
        return new OrderDto(
            totalAmount: $order->total_amount ?? '0.00',
        );
    }

    public function toArray(): array
    {
        return [
            'total_amount' => $this->totalAmount,
        ];
    }

    public function setTotalAmount(float|string $totalAmount): self
    {
        $this->totalAmount = (string) $totalAmount;

        return $this;
    }

    public function addToTotalAmount(float|string $amount): self
    {
        $totalAmount = floatval($this->totalAmount) + floatval($amount);

        return $this->setTotalAmount($totalAmount);
    }

    public function removeFromTotalAmount(float|string $amount): self
    {
        $totalAmount = floatval($this->totalAmount) - floatval($amount);

        return $this->setTotalAmount($totalAmount);
    }
}
