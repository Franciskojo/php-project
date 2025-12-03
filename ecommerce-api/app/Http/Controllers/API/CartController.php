<?php



namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
    }

    public function index(Request $request) {
        $cart = $this->cartService->getCart($request->user()->id);
        $cart->load('items.product');
        return new CartResource($cart);
    }

    public function add(CartAddRequest $request) {
        $cart = $this->cartService->getCart($request->user()->id);
        $this->cartService->addItem($cart, $request->product_id, $request->quantity);
        return response()->json(['message' => 'Item added to cart']);
    }

    public function update(CartUpdateRequest $request) {
        $this->cartService->updateItem($request->cart_item_id, $request->quantity);
        return response()->json(['message' => 'Cart item updated']);
    }

    public function remove($id) {
        $this->cartService->removeItem($id);
        return response()->json(['message' => 'Item removed from cart']);
    }

    public function checkout(CheckoutRequest $request) {
        try {
            $cart = $this->cartService->getCart($request->user()->id);
            $order = $this->cartService->checkout($cart, $request->user()->id);

            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
