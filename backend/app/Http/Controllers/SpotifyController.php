<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\SpotifyToken;
use App\Models\User;
use Carbon\Carbon;

class SpotifyController extends Controller
{
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;

    public function __construct()
    {
        $this->client_id = env('SPOTIFY_CLIENT_ID');
        $this->client_secret = env('SPOTIFY_CLIENT_SECRET');
        $this->redirect_uri = env('SPOTIFY_REDIRECT_URI');
    }

    public function redirectToSpotify()
    {
        $scopes = 'user-read-private user-read-email user-read-playback-state user-modify-playback-state playlist-read-private';

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'scope' => $scopes,
            'redirect_uri' => $this->redirect_uri,
        ]);

        return redirect("https://accounts.spotify.com/authorize?$query");
    }

    public function handleSpotifyCallback(Request $request)
    {
        $code = $request->input('code');

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.spotify.redirect'),
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        $data = $response->json();

        // Save to session (or DB if you're logged in)
        session([
            'spotify_access_token' => $data['access_token'],
            'spotify_refresh_token' => $data['refresh_token'],
            'spotify_expires_in' => $data['expires_in'],
        ]);

        return redirect('/spotify/profile');
    }

    protected function withToken()
    {
        return Http::withToken(Session::get('access_token'));
    }

    public function getUserProfile()
    {
        $accessToken = session('spotify_access_token');

        if (!$accessToken) {
            return response()->json(['error' => 'No access token found.'], 401);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.spotify.com/v1/me');

        return $response->json();
    }

    public function getUserPlaylists()
    {
        $user = Auth::user() ?? User::first(); // fallback if no auth for testing

        $spotifyToken = SpotifyToken::where('user_id', $user->id)->first();

        if (!$spotifyToken || !$spotifyToken->access_token) {
            return response()->json(['error' => 'No access token found.'], 401);
        }


        if (Carbon::now()->greaterThan($spotifyToken->expires_at)) {
            // Refresh token if expired
            $refresh = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $spotifyToken->refresh_token,
                'client_id' => config('services.spotify.client_id'),
                'client_secret' => config('services.spotify.client_secret'),
            ]);

            $refreshData = $refresh->json();

            if (isset($refreshData['access_token'])) {
                $spotifyToken->access_token = $refreshData['access_token'];
                $spotifyToken->expires_at = now()->addSeconds($refreshData['expires_in']);
                $spotifyToken->save();
            } else {
                return response()->json(['error' => 'Failed to refresh token.'], 401);
            }
        }

        // Call Spotify's API with the Bearer token
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $spotifyToken->access_token,
        ])->get('https://api.spotify.com/v1/me/playlists');

        return $response->json();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'track');

        $response = $this->withToken()->get('https://api.spotify.com/v1/search', [
            'q' => $query,
            'type' => $type,
        ]);

        return $response->json();
    }

    public function play(Request $request)
    {
        return $this->withToken()->put('https://api.spotify.com/v1/me/player/play', $request->all());
    }

    public function pause()
    {
        return $this->withToken()->put('https://api.spotify.com/v1/me/player/pause');
    }

    public function next()
    {
        return $this->withToken()->post('https://api.spotify.com/v1/me/player/next');
    }

    public function previous()
    {
        return $this->withToken()->post('https://api.spotify.com/v1/me/player/previous');
    }

    protected function getValidAccessToken()
    {
        $token = SpotifyToken::where('user_id', Auth::id())->first();

        if (!$token) {
            abort(401, 'No access token found.');
        }

        // Check if expired
        if (Carbon::now()->greaterThan($token->expires_at)) {
            // Refresh the token
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token->refresh_token,
                'client_id' => config('services.spotify.client_id'),
                'client_secret' => config('services.spotify.client_secret'),
            ]);

            $data = $response->json();

            if (isset($data['access_token'])) {
                $token->access_token = $data['access_token'];
                $token->expires_at = Carbon::now()->addSeconds($data['expires_in']);
                $token->save();
            } else {
                abort(401, 'Could not refresh access token.');
            }
        }

        return $token->access_token;
    }

}