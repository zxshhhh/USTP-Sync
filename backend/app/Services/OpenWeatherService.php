<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5/';
    }

    public function getCurrentWeather($city)
    {
        $response = Http::get($this->baseUrl . 'weather', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        return $response->json();
    }

    public function getForecast($city)
    {
        $response = Http::get($this->baseUrl . 'forecast', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        return $response->json();
    }
}
