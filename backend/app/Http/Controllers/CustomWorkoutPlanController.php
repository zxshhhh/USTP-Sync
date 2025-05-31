<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CustomWorkoutPlanController extends Controller
{
    public function generateCustomWorkoutPlan(Request $request)
    {
        $url = config('services.rapidapi.custom_workout_url');
        $host = config('services.rapidapi.host');
        $key  = config('services.rapidapi.key');

        try {
            $validated = $request->validate([
                'goal' => 'required|string',
                'fitness_level' => 'required|string',
                'preferences' => 'required|array',
                'health_conditions' => 'required|array',
                'schedule.days_per_week' => 'required|integer',
                'schedule.session_duration' => 'required|integer',
                'plan_duration_weeks' => 'required|integer',
                'custom_goals' => 'required|array',
                'lang' => 'required|string'
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-rapidapi-host' => $host,
                'x-rapidapi-key' => $key,
            ])->post($url, $validated);

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json([
                    'error' => 'RapidAPI Error',
                    'message' => $response->body()
                ], $response->status());
            }

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unexpected Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
