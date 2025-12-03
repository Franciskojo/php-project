<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    // GET /api/orders (user)
    public function userOrders(Request $request) {
        $orders = $this->orderService->getUserOrders($request->user()->id);
        return OrderResource::collection($orders);
    }

    // GET /api/orders/all (admin)
    public function allOrders(Request $request) {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $orders = $this->orderService->getAllOrders();
        return OrderResource::collection($orders);
    }

    // POST /api/payment/simulate
    public function simulatePayment(Request $request) {
        $request->validate(['order_id' => 'required|exists:orders,id']);
        $order = $this->orderService->simulatePayment($request->order_id);

        return response()->json([
            'payment_successful' => true,
            'order' => new OrderResource($order)
        ]);
    }
}

