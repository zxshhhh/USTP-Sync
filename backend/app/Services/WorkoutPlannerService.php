<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WorkoutPlannerService
{
    protected $host;
    protected $key;

    public function __construct()
    {
        $this->host = env('AI_WORKOUT_API_HOST');
        $this->key = env('AI_WORKOUT_API_KEY');
    }

    private function post($endpoint, $payload)
    {
        return Http::withHeaders([
            'X-RapidAPI-Key' => $this->key,
            'X-RapidAPI-Host' => $this->host,
            'Content-Type' => 'application/json',
        ])->post("https://{$this->host}/$endpoint", $payload)->json();
    }

    public function generateWorkoutPlan($data)
    {
        return $this->post('generateWorkoutPlan', $data);
    }

    public function getExerciseDetails($data)
    {
        return $this->post('exerciseDetails', $data);
    }

    public function getNutritionAdvice($data)
    {
        return $this->post('nutritionAdvice', $data);
    }

    public function generateCustomWorkoutPlan($data)
    {
        return $this->post('customWorkoutPlan', $data);
    }

    public function analyzeFoodPlate($data)
    {
        return $this->post('analyzeFoodPlate', $data);
    }
}
