<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\FitbitToken;

class FitbitService
{
    public function getAccessToken($userId = 1)
    {
        $token = FitbitToken::where('user_id', $userId)->first();

        if (!$token) return null;

        if ($token->expires_at->isPast()) {
            $token = $this->refreshAccessToken($userId);
        }

        return $token;
    }

    public function refreshAccessToken($userId)
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

        if (!isset($data['access_token'])) return null;

        $fitbitToken->update([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'scope' => $data['scope'],
            'expires_in' => $data['expires_in'],
            'expires_at' => now()->addSeconds($data['expires_in']),
        ]);

        return $fitbitToken;
    }

    public function getData($endpoint, $userId = 1)
    {
        $token = $this->getAccessToken($userId);

        if (!$token) return ['error' => 'Invalid or missing token'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->access_token,
        ])->get($endpoint);

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => 'Request failed', 'details' => $response->json()];
    }

    public function saveAccessToken($data, $userId = 1)
    {
        return FitbitToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'scope' => $data['scope'],
                'expires_in' => $data['expires_in'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );
    }
}
