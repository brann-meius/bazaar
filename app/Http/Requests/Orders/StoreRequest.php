<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => [
                'required',
                'array',
                'max:50',
            ],
            'products.*.id' => [
                'required',
                'distinct',
                'integer',
            ],
            'products.*.count' => [
                'required',
                'distinct',
                'integer',
            ],
        ];
    }
}
