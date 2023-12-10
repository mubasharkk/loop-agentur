<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'payed'    => $this->payed,
            'value'    => $this->totalValue(),
            'customer' => new CustomerResource($this->customer),
            'products' => ProductResource::collection($this->products),
        ];
    }
}
