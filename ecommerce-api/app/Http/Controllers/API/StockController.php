<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RestockRequest;
use App\Http\Resources\StockResource;
use App\Services\StockService;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService) {
        $this->stockService = $stockService;
    }

    public function restock(RestockRequest $request) {
        $product = $this->stockService->restock($request->product_id, $request->quantity);
        return new StockResource($product);
    }
}
