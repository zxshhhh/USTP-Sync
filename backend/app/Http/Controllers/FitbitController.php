<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FitbitToken;

class FitbitController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => env('FITBIT_CLIENT_ID'),
            'response_type' => 'code',
            'scope' => 'activity heartrate sleep profile',
            'redirect_uri' => env('FITBIT_REDIRECT_URI'),
        ]);

        return redirect("https://www.fitbit.com/oauth2/authorize?{$query}");
    }

    public function callback(Request $request)
    {
        $response = Http::asForm()
            ->withBasicAuth(env('FITBIT_CLIENT_ID'), env('FITBIT_CLIENT_SECRET'))
            ->post('https://api.fitbit.com/oauth2/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => env('FITBIT_REDIRECT_URI'),
            ]);

        $data = $response->json();

        FitbitToken::updateOrCreate(
            ['user_id' => 1], // Or use Auth::id() if user is authenticated
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'scope' => $data['scope'],
                'expires_in' => $data['expires_in'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );

        return response()->json(['message' => 'Token saved successfully', 'token_data' => $data]);

    }

    public function getProfile()
    {
        $userId = 1; // Replace with Auth::id() if using auth
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        // Check if token exists
        if (!$fitbitToken) {
            return response()->json(['error' => 'No token found'], 404);
        }

        // Check if token is expired
        if ($fitbitToken->expires_at->isPast()) {
            // Optional: call $this->refreshToken($userId); here if you’ve implemented token refresh
            return response()->json(['error' => 'Access token expired. Please refresh the token.'], 401);
        }

        // Auto-refresh if token expired
        if ($fitbitToken->expires_at->isPast()) {
            $newToken = $this->refreshAccessToken($userId);
            if (!$newToken) {
                return response()->json(['error' => 'Failed to refresh token'], 401);
            }

            $fitbitToken = FitbitToken::where('user_id', $userId)->first(); // Reload updated token
        }

        // Make Fitbit API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $fitbitToken->access_token,
        ])->get('https://api.fitbit.com/1/user/-/profile.json');

        if ($response->successful()) {
            return $response->json(); // Return the Fitbit user profile
        } else {
            return response()->json(['error' => 'Failed to fetch profile', 'details' => $response->json()], 400);
        }
    }

    private function refreshAccessToken($userId)
    {
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        if (!$fitbitToken || !$fitbitToken->refresh_token) {
            return null;
        }

        $response = Http::asForm()
            ->withBasicAuth(env('FITBIT_CLIENT_ID'), env('FITBIT_CLIENT_SECRET'))
            ->post('https://api.fitbit.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $fitbitToken->refresh_token,
            ]);

        $data = $response->json();

        if (isset($data['access_token'])) {
            // Update the token in database
            $fitbitToken->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'scope' => $data['scope'],
                'expires_in' => $data['expires_in'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            return $data['access_token'];
        }

        return null;
    }

    public function getSteps()
    {
        $userId = 1; // Replace with Auth::id()
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        if (!$fitbitToken) return response()->json(['error' => 'No token'], 404);
        if ($fitbitToken->expires_at->isPast()) {
            $newToken = $this->refreshAccessToken($userId);
            if (!$newToken) return response()->json(['error' => 'Token refresh failed'], 401);
            $fitbitToken = FitbitToken::where('user_id', $userId)->first();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $fitbitToken->access_token,
        ])->get('https://api.fitbit.com/1/user/-/activities/steps/date/today/7d.json');

        return $response->successful()
            ? $response->json()
            : response()->json(['error' => 'Failed to get steps', 'details' => $response->json()], 400);
    }

    public function getSleep()
    {
        $userId = 1;
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        if (!$fitbitToken) return response()->json(['error' => 'No token'], 404);

        if ($fitbitToken->expires_at->isPast()) {
            $newToken = $this->refreshAccessToken($userId);
            if (!$newToken) return response()->json(['error' => 'Token refresh failed'], 401);
            $fitbitToken = FitbitToken::where('user_id', $userId)->first();
        }

        // ✅ Use Carbon to get today's date in correct format
        $date = \Carbon\Carbon::now()->toDateString();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $fitbitToken->access_token,
        ])->get("https://api.fitbit.com/1.2/user/-/sleep/date/{$date}.json");

        return $response->successful()
            ? $response->json()
            : response()->json([
                'error' => 'Failed to get sleep data',
                'details' => $response->json()
            ], 400);
    }

    public function getHeartRate()
    {
        $userId = 1;
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        if (!$fitbitToken) return response()->json(['error' => 'No token'], 404);

        if ($fitbitToken->expires_at->isPast()) {
            $newToken = $this->refreshAccessToken($userId);
            if (!$newToken) return response()->json(['error' => 'Token refresh failed'], 401);
            $fitbitToken = FitbitToken::where('user_id', $userId)->first();
        }

        $date = \Carbon\Carbon::now()->toDateString();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $fitbitToken->access_token,
        ])->get("https://api.fitbit.com/1/user/-/activities/heart/date/{$date}/1d.json");

        return $response->successful()
            ? $response->json()
            : response()->json(['error' => 'Failed to get heart rate', 'details' => $response->json()], 400);
    }

    public function getCalories()
    {
        $userId = 1;
        $fitbitToken = FitbitToken::where('user_id', $userId)->first();

        if (!$fitbitToken) return response()->json(['error' => 'No token'], 404);

        if ($fitbitToken->expires_at->isPast()) {
            $newToken = $this->refreshAccessToken($userId);
            if (!$newToken) return response()->json(['error' => 'Token refresh failed'], 401);
            $fitbitToken = FitbitToken::where('user_id', $userId)->first();
        }

        $date = \Carbon\Carbon::now()->toDateString();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $fitbitToken->access_token,
        ])->get("https://api.fitbit.com/1/user/-/activities/calories/date/{$date}/1d.json");

        return $response->successful()
            ? $response->json()
            : response()->json(['error' => 'Failed to get calories', 'details' => $response->json()], 400);
    }


}
