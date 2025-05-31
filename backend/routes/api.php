<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpoonacularController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FitbitController;
use App\Http\Controllers\AudiusController;
use App\Http\Controllers\WorkoutPlannerController;
use App\Http\Controllers\NutritionAdviceController;
use App\Http\Controllers\ExerciseDetailsController;
use App\Http\Controllers\CustomWorkoutPlanController;
use App\Http\Controllers\FoodPlateAnalyzerController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // ============================= Spoonacular API routes
    Route::get('/recipes/search', [SpoonacularController::class, 'search']);
    Route::post('/recipes/search', [SpoonacularController::class, 'search']);
    Route::get('/recipes/{id}', [SpoonacularController::class, 'getRecipe']);
    Route::get('/ingredients/autocomplete', [SpoonacularController::class, 'autocomplete']);
    Route::post('/ingredients/autocomplete', [SpoonacularController::class, 'autocomplete']);
    // ============================= OpenWeather API routes
    Route::get('/weather/current', [WeatherController::class, 'current']);
    Route::post('/weather/current', [WeatherController::class, 'current']);
    Route::get('/weather/forecast', [WeatherController::class, 'forecast']);
    Route::post('/weather/forecast', [WeatherController::class, 'forecast']);
    // ============================= Fitbit API routes
    Route::get('/fitbit/redirect', [FitbitController::class, 'redirect']);
    Route::get('/fitbit/callback', [FitbitController::class, 'callback']);
    Route::get('/fitbit/profile', [FitbitController::class, 'getProfile']);
    Route::get('/fitbit/steps', [FitbitController::class, 'getSteps']);
    Route::get('/fitbit/sleep', [FitbitController::class, 'getSleep']);
    Route::get('/fitbit/heartrate', [FitbitController::class, 'getHeartRate']);
    Route::get('/fitbit/calories', [FitbitController::class, 'getCalories']);
    // ============================= Audios API routes
    Route::get('/trending', [AudiusController::class, 'trending']);
    Route::get('/search/{query}', [AudiusController::class, 'search']);
    Route::get('/play', [AudiusController::class, 'play']);
    Route::get('/pause', [AudiusController::class, 'pause']);
    Route::get('/skip', [AudiusController::class, 'skip']);
    // ============================= AI Workout Plan API routes
    Route::post('/generate-workout-plan', [WorkoutPlannerController::class, 'generateWorkoutPlan']);
    Route::post('/nutrition-advice', [NutritionAdviceController::class, 'getNutritionAdvice']);
    Route::post('/exercise-details', [ExerciseDetailsController::class, 'getExerciseDetails']);
    Route::post('/custom-workout-plan', [CustomWorkoutPlanController::class, 'generateCustomWorkoutPlan']);
    Route::post('/analyze-food-plate', [FoodPlateAnalyzerController::class, 'analyze']);
});