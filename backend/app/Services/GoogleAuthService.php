<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthService
{
    /**
     * Verify Google ID token and get user information.
     *
     * @param string $idToken The ID token received from the frontend.
     * @return array|null User information if valid, null otherwise.
     */
    public function verifyGoogleIdToken(string $idToken): ?array
    {
        try {
            $client = new Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($idToken);

            if ($payload) {
                return $payload;
            }
        } catch (Exception $e) {
            Log::error('Google ID Token Verification Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Find or create a user based on Google information.
     *
     * @param array $googleUser The user information returned from Google verification.
     * @return User
     */
    public function findOrCreateUser(array $googleUser): User
    {
        $user = User::where('google_id', $googleUser['sub'])->first();

        if ($user) {
            return $user;
        }

        // Create a new user
        return User::create([
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'google_id' => $googleUser['sub'],
            'password' => bcrypt(Str::random(16)), // Generate a random password if you don't use password-based login for Google users
        ]);
    }
}