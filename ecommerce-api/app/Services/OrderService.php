<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    // Fetch order history for a user
    public function getUserOrders($userId) {
        return Order::with('items.product')
                    ->where('user_id', $userId)
                    ->orderByDesc('created_at')
                    ->get();
    }

    // Fetch all orders (Admin)
    public function getAllOrders() {
        return Order::with('items.product', 'user')
                    ->orderByDesc('created_at')
                    ->get();
    }

    // Simulate payment
    public function simulatePayment($orderId) {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'paid']);
        return $order;
    }
}
