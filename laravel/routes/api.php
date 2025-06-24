<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('terrains', TerrainController::class);
    Route::apiResource('bookings', BookingController::class);
    Route::apiResource('reviews', ReviewController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('favorites', FavoriteController::class)->except(['show', 'update']);
    Route::delete('favorites/{terrain}', [FavoriteController::class, 'destroy']);
});

// Public routes
Route::get('terrains', [TerrainController::class, 'index']);
Route::get('terrains/{terrain}', [TerrainController::class, 'show']);
Route::get('reviews', [ReviewController::class, 'index']);
