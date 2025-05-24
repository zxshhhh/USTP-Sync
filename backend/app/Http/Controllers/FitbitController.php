<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FitbitService;

class FitbitController extends Controller
{
    protected $fitbitService;

    public function __construct(FitbitService $fitbitService)
    {
        $this->fitbitService = $fitbitService;
    }

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

        $this->fitbitService->saveAccessToken($data);

        return response()->json(['message' => 'Token saved successfully', 'token_data' => $data]);
    }

    public function getProfile()
    {
        $data = $this->fitbitService->getData('https://api.fitbit.com/1/user/-/profile.json');
        return response()->json($data, isset($data['error']) ? 400 : 200);
    }

    public function getSteps()
    {
        $endpoint = 'https://api.fitbit.com/1/user/-/activities/steps/date/today/7d.json';
        $data = $this->fitbitService->getData($endpoint);
        return response()->json($data, isset($data['error']) ? 400 : 200);
    }

    public function getSleep()
    {
        $date = now()->toDateString();
        $endpoint = "https://api.fitbit.com/1.2/user/-/sleep/date/{$date}.json";
        $data = $this->fitbitService->getData($endpoint);
        return response()->json($data, status: isset($data['error']) ? 400 : 200);
    }

    public function getHeartRate()
    {
        $date = now()->toDateString();
        $endpoint = "https://api.fitbit.com/1/user/-/activities/heart/date/{$date}/1d.json";
        $data = $this->fitbitService->getData($endpoint);
        return response()->json($data, isset($data['error']) ? 400 : 200);
    }

    public function getCalories()
    {
        $date = now()->toDateString();
        $endpoint = "https://api.fitbit.com/1/user/-/activities/calories/date/{$date}/1d.json";
        $data = $this->fitbitService->getData($endpoint);
        return response()->json($data, isset($data['error']) ? 400 : 200);
    }
}
