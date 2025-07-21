<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

// Frontend Routes for Music Rental System
Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/catalog', [FrontendController::class, 'catalog'])->name('frontend.catalog');
Route::get('/instrument/{id}', [FrontendController::class, 'instrumentDetail'])->name('frontend.instrument-detail');
Route::get('/booking/{instrumentId}', [FrontendController::class, 'booking'])->name('frontend.booking');
Route::post('/booking', [FrontendController::class, 'storeBooking'])->name('frontend.store-booking');
Route::get('/booking-success/{orderId}', [FrontendController::class, 'bookingSuccess'])->name('frontend.booking-success');
Route::post('/calculate-price', [FrontendController::class, 'calculatePrice'])->name('frontend.calculate-price');
Route::get('/track-order', function() { return view('frontend.track-order-form'); })->name('frontend.track-order-form');
Route::post('/track-order', [FrontendController::class, 'trackOrder'])->name('frontend.track-order');
