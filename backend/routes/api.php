<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpoonacularController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FitbitController;
use App\Http\Controllers\NylasController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ============================= Spoonacular API routes
Route::get('/recipes/search', [SpoonacularController::class, 'search']);
Route::get('/recipes/{id}', [SpoonacularController::class, 'getRecipe']);
Route::get('/ingredients/autocomplete', [SpoonacularController::class, 'autocomplete']);

Route::get('/weather/current', [WeatherController::class, 'current']);
Route::get('/weather/forecast', [WeatherController::class, 'forecast']);

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

// Authentication routes (might be part of your existing auth system)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);

    // Calendar routes
    Route::get('/calendar/events', [CalendarController::class, 'index']);
    Route::post('/calendar/events', [CalendarController::class, 'store']);
    Route::put('/calendar/events/{eventId}', [CalendarController::class, 'update']);
    Route::delete('/calendar/events/{eventId}', [CalendarController::class, 'destroy']);
});

Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// ============================= Fitbit API routes
Route::get('/fitbit/redirect', [FitbitController::class, 'redirect']);
Route::get('/fitbit/callback', [FitbitController::class, 'callback']);
Route::get('/fitbit/profile', [FitbitController::class, 'getProfile']);
Route::get('/fitbit/steps', [FitbitController::class, 'getSteps']);
Route::get('/fitbit/sleep', [FitbitController::class, 'getSleep']);
Route::get('/fitbit/heartrate', [FitbitController::class, 'getHeartRate']);
Route::get('/fitbit/calories', [FitbitController::class, 'getCalories']);