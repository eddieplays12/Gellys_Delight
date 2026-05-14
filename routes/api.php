<?php

use App\Http\Controllers\Admin\AuthController as AdminSessionAuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Product routes
Route::get('/products', [ProductController::class, 'index']);

// User cookie/session routes
Route::middleware('web')->group(function () {
    Route::post('/users/register', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::get('/users/{userId}/orders', [OrderController::class, 'getOrdersByUser']);
    Route::post('/ratings', [RatingController::class, 'createRating']);

    Route::middleware('user.auth')->group(function () {
        Route::get('/users/me', [UserController::class, 'me']);
        Route::put('/users/me', [UserController::class, 'updateMe']);
        Route::post('/users/logout', [UserController::class, 'logout']);

        Route::get('/users/me/orders', [OrderController::class, 'getMyOrders']);
    });
});

// Public rating route
Route::get('/products/{productId}/ratings', [RatingController::class, 'getProductRatings']);

// Admin-only order status should stay protected in admin API later
// Route::put('/orders/{orderId}/status', [OrderController::class, 'updateOrderStatus']);


// Admin API routes use Laravel session cookies instead of bearer tokens.
Route::middleware('web')->prefix('admin')->group(function () {
    Route::post('/login', [AdminSessionAuthController::class, 'apiLogin']);

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminSessionAuthController::class, 'apiLogout']);
        Route::get('/products', [AdminController::class, 'getProducts']);
        Route::post('/products', [AdminController::class, 'createProduct']);
        Route::put('/products/{productId}', [AdminController::class, 'updateProduct']);
        Route::delete('/products/{productId}', [AdminController::class, 'deleteProduct']);
        Route::get('/orders', [AdminController::class, 'getOrders']);
        Route::get('/ratings', [AdminController::class, 'getRatings']);
    });
});
