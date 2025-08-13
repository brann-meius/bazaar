<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Order $resource
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'customer_name' => $this->resource->user->name,
            'customer_email' => $this->resource->user->email,
            'total_amount' => $this->resource->total_amount,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
