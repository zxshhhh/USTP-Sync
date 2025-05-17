<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/auth/spotify', [SpotifyController::class, 'redirectToSpotify']);
Route::get('/callback/spotify', [SpotifyController::class, 'handleSpotifyCallback']);

// APIs
Route::get('/spotify/profile', [SpotifyController::class, 'getUserProfile']);
Route::get('/spotify/playlists', [SpotifyController::class, 'getUserPlaylists']);
Route::get('/spotify/search', [SpotifyController::class, 'search']);
Route::post('/spotify/play', [SpotifyController::class, 'play']);
Route::post('/spotify/pause', [SpotifyController::class, 'pause']);
Route::post('/spotify/next', [SpotifyController::class, 'next']);
Route::post('/spotify/previous', [SpotifyController::class, 'previous']);

require __DIR__.'/auth.php';
