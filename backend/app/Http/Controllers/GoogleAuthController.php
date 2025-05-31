<?php

namespace App\Http\Controllers;

use App\Services\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    protected $googleAuthService;

    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    /**
     * Handle the Google login callback.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleGoogleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        $idToken = $request->input('id_token');

        $googleUser = $this->googleAuthService->verifyGoogleIdToken($idToken);

        if (!$googleUser) {
            return response()->json(['message' => 'Invalid Google ID token.'], 401);
        }

        $user = $this->googleAuthService->findOrCreateUser($googleUser);

        // Authenticate the user and generate an API token
        Auth::login($user); // Optional: if you want to use Laravel's session-based auth alongside API tokens

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login successful!'
        ]);
    }
}