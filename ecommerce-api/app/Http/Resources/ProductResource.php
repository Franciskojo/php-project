<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    public function toArray($request)
  {
    return [
        'id'          => $this->id,
        'name'        => $this->name,
        'description' => $this->description,
        'price'       => (float) $this->price,
        'stock'       => $this->stock,

        'image_url'   => $this->image_url
            ? asset('storage/' . $this->image_url)
            : null,

        'created_at'  => $this->created_at->toISOString(),
        'updated_at'  => $this->updated_at->toISOString(),
    ];
  }

}
