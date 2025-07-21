<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;

// API routes for instrument rental system

// Booking API routes
Route::prefix('booking')->group(function () {
    Route::post('/create-order', [BookingController::class, 'createOrder']);
    Route::get('/order/{orderNumber}', [BookingController::class, 'getOrder']);
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
        'timestamp' => now()
    ]);
});
