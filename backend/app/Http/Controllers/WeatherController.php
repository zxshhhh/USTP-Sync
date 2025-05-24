<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenWeatherService;

class WeatherController extends Controller
{
    protected $weather;

    public function __construct(OpenWeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function current(Request $request)
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        $data = $this->weather->getCurrentWeather($request->city);
        return response()->json($data);
    }

    public function forecast(Request $request)
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        $data = $this->weather->getForecast($request->city);
        return response()->json($data);
    }
}
