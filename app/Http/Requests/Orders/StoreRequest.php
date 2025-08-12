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
            ],
            'products.*' => [
                'required',
                'integer',
            ],
        ];
    }
}
