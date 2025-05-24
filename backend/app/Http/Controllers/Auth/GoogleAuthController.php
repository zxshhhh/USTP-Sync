<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class GoogleAuthController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function redirectToGoogle()
    {
        $client = $this->googleCalendarService->getClient();
        $authUrl = $client->createAuthUrl();
        return response()->json(['auth_url' => $authUrl]);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->googleCalendarService->getClient();

        if ($request->has('code')) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            if (isset($accessToken['error'])) {
                return response()->json(['error' => 'Authentication failed: ' . $accessToken['error_description']], 400);
            }

            // Store tokens in user's database record
            $user = Auth::user(); // Assuming you have a logged-in user
            if ($user) {
                $user->google_access_token = $accessToken['access_token'];
                $user->google_refresh_token = $accessToken['refresh_token'] ?? $user->google_refresh_token; // Keep existing if not new
                $user->google_token_expires_at = Carbon::now()->addSeconds($accessToken['expires_in']);
                $user->save();
            }

            return response()->json(['message' => 'Google Calendar connected successfully!'], 200);
        }

        return response()->json(['error' => 'No authorization code received.'], 400);
    }
}