<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitbitController;
use App\Http\Controllers\OpenFoodController;
use App\Http\Controllers\SpotifyController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ============================= Fitbit API Routes
Route::get('/fitbit/redirect', [FitbitController::class, 'redirect']);
Route::get('/fitbit/callback', [FitbitController::class, 'callback']);
Route::get('/fitbit/profile', [FitbitController::class, 'getProfile']);
Route::get('/fitbit/steps', [FitbitController::class, 'getSteps']);
Route::get('/fitbit/sleep', [FitbitController::class, 'getSleep']);
Route::get('/fitbit/heartrate', [FitbitController::class, 'getHeartRate']);
Route::get('/fitbit/calories', [FitbitController::class, 'getCalories']);

// ============================ OpenFoodFacts API Routes
Route::get('/openfood/search', [OpenFoodController::class, 'searchProduct']);
Route::get('/openfood/product/{barcode}', [OpenFoodController::class, 'getProductByBarcode']);

// ============================ Spotify API
Route::get('/spotify/callback', [SpotifyController::class, 'handleSpotifyCallback']);