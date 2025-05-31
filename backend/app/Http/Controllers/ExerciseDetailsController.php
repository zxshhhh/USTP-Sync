<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ExerciseDetailsController extends Controller
{
    public function getExerciseDetails(Request $request)
    {
        $url  = config('services.rapidapi.exercise_details_url');
        $host = config('services.rapidapi.host');
        $key  = config('services.rapidapi.key');

        try {
            $validated = $request->validate([
                'exercise_name' => 'required|string',
                'lang' => 'required|string'
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-rapidapi-host' => $host,
                'x-rapidapi-key' => $key,
            ])->post($url, $validated);

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } elseif ($response->status() === 429 || str_contains($response->body(), 'exceeded the MONTHLY quota')) {
                return response()->json([
                    'error' => 'API Limit Reached',
                    'message' => 'You have exceeded your monthly quota on RapidAPI. Please upgrade your plan or wait for the reset.'
                ], 429);
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