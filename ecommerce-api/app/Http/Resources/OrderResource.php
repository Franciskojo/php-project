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
    public function toArray($request) {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
