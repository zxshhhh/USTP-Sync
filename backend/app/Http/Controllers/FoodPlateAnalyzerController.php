<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class FoodPlateAnalyzerController extends Controller
{
    public function analyze(Request $request)
    {
        $url  = config('services.rapidapi.analyze_food_url');
        $host = config('services.rapidapi.host');
        $key  = config('services.rapidapi.key');

        try {
            $validated = $request->validate([
                'imageUrl' => 'required|url',
                'lang' => 'required|string',
            ]);

            $query = http_build_query([
                'imageUrl' => $validated['imageUrl'],
                'lang' => $validated['lang'],
                'noqueue' => 1
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-rapidapi-host' => $host,
                'x-rapidapi-key' => $key,
            ])->post("{$url}?{$query}");

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
