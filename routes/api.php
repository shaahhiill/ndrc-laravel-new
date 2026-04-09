<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    
    // Orders
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'index']);
});
