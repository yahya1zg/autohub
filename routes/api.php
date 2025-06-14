<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController; // Yeni controller'ı ekle

// ... (Public rotalar aynı kalacak) ...
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);
Route::post('/ai/recommend-car', [AiController::class, 'recommendCar']);


// --- PROTECTED ROUTES (Sadece giriş yapmış kullanıcıların erişebileceği rotalar) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // Favori Rotaları
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{carId}', [FavoriteController::class, 'toggle']);
    
    // Satın Alma Rotası
    Route::post('/purchase', [PurchaseController::class, 'store']);

    // Sepet Rotaları
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/remove', [CartController::class, 'remove']);

    // Sipariş Rotası (YENİ)
    Route::get('/orders', [OrderController::class, 'index']);
});
