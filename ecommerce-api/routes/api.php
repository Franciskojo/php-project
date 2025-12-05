<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\OrderController;


// -------------------------------
// AUTH ROUTES
// -------------------------------
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post("logout", [AuthController::class, "logout"]);
    Route::get("me", [AuthController::class, "me"]);
});


// -------------------------------
// PUBLIC PRODUCT ROUTES
// -------------------------------
Route::get("products", [ProductController::class, 'index']);
Route::get("products/{product}", [ProductController::class, 'show']);


// -------------------------------
// ADMIN ROUTES
// -------------------------------
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Product Management
    Route::post("products", [ProductController::class, 'store']);
    Route::put("products/{product}", [ProductController::class, 'update']);
    Route::patch("products/{product}", [ProductController::class, 'update']);
    Route::delete("products/{product}", [ProductController::class, 'destroy']);

    // Stock Management (cleaner)
    Route::post('/products/restock', [StockController::class, 'restock']);

    // Admin view all orders
    Route::get('/orders/all', [OrderController::class, 'allOrders']);
});


// -------------------------------
// CART + USER ORDER ROUTES
// -------------------------------
Route::middleware('auth:sanctum')->group(function() {

    // CART
    Route::get("cart", [CartController::class, 'index']);
    Route::post("cart/add", [CartController::class, 'add']);
    Route::patch("cart/update", [CartController::class, 'update']);
    Route::delete("cart/remove/{id}", [CartController::class, 'remove']);
    Route::post("cart/checkout", [CartController::class, 'checkout']);

    // USER ORDER HISTORY
    Route::get('/orders', [OrderController::class, 'userOrders']);

    // PAYMENT SIMULATION
    Route::post("payment/simulate", [OrderController::class, 'simulatePayment']);
});



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
