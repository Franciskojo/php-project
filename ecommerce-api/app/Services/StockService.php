<?php

namespace App\Services;

use App\Models\Product;

class StockService
{
    public function restock($productId, $quantity) {
        $product = Product::findOrFail($productId);
        $product->increment('stock', $quantity);
        return $product;
    }
}
