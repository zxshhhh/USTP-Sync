<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class NutritionAdviceController extends Controller
{
    public function getNutritionAdvice(Request $request)
    {
        $url = config('services.rapidapi.nutrition_url');
        $host = config('services.rapidapi.host');
        $key  = config('services.rapidapi.key');

        try {
            $validated = $request->validate([
                'goal' => 'required|string',
                'dietary_restrictions' => 'required|array',
                'current_weight' => 'required|numeric',
                'target_weight' => 'required|numeric',
                'daily_activity_level' => 'required|string',
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
