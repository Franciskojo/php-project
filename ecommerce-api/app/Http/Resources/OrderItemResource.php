<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->price,
            ],
            'quantity' => $this->quantity,
            'subtotal' => $this->quantity * $this->price,
        ];
    }
}
