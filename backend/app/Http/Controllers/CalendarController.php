<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    private function authenticateGoogleClient()
    {
        $user = Auth::user();

        if (!$user || !$user->google_access_token) {
            abort(403, 'User not authenticated with Google Calendar.');
        }

        $accessToken = json_decode($user->google_access_token, true);

        // Check if token is expired and refresh if necessary
        if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse($user->google_token_expires_at)) && $user->google_refresh_token) {
            $newAccessToken = $this->googleCalendarService->refreshToken($user->google_refresh_token);
            $user->google_access_token = json_encode($newAccessToken);
            $user->google_token_expires_at = Carbon::now()->addSeconds($newAccessToken['expires_in']);
            $user->save();
            $accessToken = $newAccessToken;
        }

        $this->googleCalendarService->setAccessToken($accessToken);
    }

    public function index()
    {
        $this->authenticateGoogleClient();
        try {
            $events = $this->googleCalendarService->getEvents();
            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $this->authenticateGoogleClient();

        $validatedData = $request->validate([
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'location' => 'nullable|string|max:255',
        ]);

        try {
            $eventData = [
                'summary' => $validatedData['summary'],
                'description' => $validatedData['description'],
                'location' => $validatedData['location'],
                'start' => [
                    'dateTime' => Carbon::parse($validatedData['start_datetime'])->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => Carbon::parse($validatedData['end_datetime'])->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
            ];

            $event = $this->googleCalendarService->createEvent($eventData);
            return response()->json($event, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create event: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $eventId)
    {
        $this->authenticateGoogleClient();

        $validatedData = $request->validate([
            'summary' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date|after_or_equal:start_datetime',
            'location' => 'nullable|string|max:255',
        ]);

        try {
            $eventData = [];
            if (isset($validatedData['summary'])) $eventData['summary'] = $validatedData['summary'];
            if (isset($validatedData['description'])) $eventData['description'] = $validatedData['description'];
            if (isset($validatedData['location'])) $eventData['location'] = $validatedData['location'];
            if (isset($validatedData['start_datetime'])) {
                $eventData['start'] = [
                    'dateTime' => Carbon::parse($validatedData['start_datetime'])->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ];
            }
            if (isset($validatedData['end_datetime'])) {
                $eventData['end'] = [
                    'dateTime' => Carbon::parse($validatedData['end_datetime'])->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ];
            }

            $event = $this->googleCalendarService->updateEvent($eventId, $eventData);
            return response()->json($event);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update event: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($eventId)
    {
        $this->authenticateGoogleClient();
        try {
            $this->googleCalendarService->deleteEvent($eventId);
            return response()->json(['message' => 'Event deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete event: ' . $e->getMessage()], 500);
        }
    }
}