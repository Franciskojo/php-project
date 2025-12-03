<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart($userId) {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function addItem($cart, $productId, $quantity) {
        $product = Product::findOrFail($productId);

        $cartItem = $cart->items()->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);
        }
        return $cart;
    }

    public function updateItem($cartItemId, $quantity) {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    public function removeItem($cartItemId) {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();
    }

    public function checkout($cart, $userId) {
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            throw new \Exception('Cart is empty');
        }

        return DB::transaction(function() use ($cart, $userId) {
            // Stock validation
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Product {$item->product->name} insufficient stock");
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'total'   => $cart->total(),
                'status'  => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}
