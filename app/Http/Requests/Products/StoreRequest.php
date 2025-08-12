<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use App\Dto\ProductDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                'decimal:0,2'
            ],
            'stock_quantity' => [
                'required',
                'integer',
                'min:1',
                'max:4294967294',
            ],
            'description' => [
                'nullable',
                'string',
                'min:3',
                'max:32768',
            ],
        ];
    }

    public function toDto(): ProductDto
    {
        return new ProductDto(
            $this->input('name'),
            $this->string('price')->value(),
            $this->integer('stock_quantity'),
            $this->input('description'),
        );
    }
}
