<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    public function checkout(Cart $cart, $user, array $data): Order
{
    if ($cart->items->count() === 0) {
            throw new Exception("Your cart is empty.");
        }

    return DB::transaction(function () use ($cart, $user, $data) {

            // 1. Validate stock
            foreach ($cart->items as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new Exception("Not enough stock for {$item->product->name}");
                }
            }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'shipping_address' => $data['shipping_address'],
            'shipping_city' => $data['shipping_city'],
            'shipping_region' => $data['shipping_region'],
            'shipping_phone' => $data['shipping_phone'],

            'billing_address' => $data['billing_address'] ?? null,
            'billing_city' => $data['billing_city'] ?? null,
            'billing_region' => $data['billing_region'] ?? null,
            'billing_phone' => $data['billing_phone'] ?? null,
            
            'total' => $cart->items->sum(fn ($i) => $i->quantity * $i->product->price),
        ]);

        // Create order items and reduce stock
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        DB::commit();

        return $order;
   
         });
    }
}
