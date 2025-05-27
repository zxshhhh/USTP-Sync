<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\AudiusService;

class AudiusController extends Controller
{
    protected $audius;

    public function __construct(AudiusService $audius)
    {
        $this->audius = $audius;
    }

    public function trending()
    {
        $tracks = $this->audius->trending();
        Session::put('audius_tracks', $tracks);
        Session::put('current_track_index', 0);

        return response()->json($tracks);
    }

    public function search($query)
    {
        try {
            $tracks = $this->audius->search($query);
            return response()->json($tracks);
        } catch (\Exception $e) {
            // Catch exceptions and return as JSON error
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function play()
    {
        $index = Session::get('current_track_index', 0);
        $tracks = Session::get('audius_tracks', []);

        return isset($tracks[$index])
            ? response()->json(['status' => 'playing', 'track' => $tracks[$index]])
            : response()->json(['message' => 'No track found to play'], 404);
    }

    public function pause()
    {
        return response()->json(['status' => 'paused']);
    }

    public function skip()
    {
        $tracks = Session::get('audius_tracks', []);
        $index = Session::get('current_track_index', 0);
        $index = ($index + 1) % count($tracks);
        Session::put('current_track_index', $index);

        return response()->json([
            'status' => 'playing',
            'track' => $tracks[$index]
        ]);
    }
}
