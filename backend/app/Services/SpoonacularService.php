<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    protected $baseUrl = config('services.spoonacular.spoon_url');
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.api_key');
    }

    public function searchRecipes($query)
    {
        return Http::get("{$this->baseUrl}/recipes/complexSearch", [
            'apiKey' => $this->apiKey,
            'query' => $query,
        ])->json();
    }

    public function getRecipeInformation($id)
    {
        return Http::get("{$this->baseUrl}/recipes/{$id}/information", [
            'apiKey' => $this->apiKey,
            'includeNutrition' => 'true',
        ])->json();
    }

    public function autocompleteIngredients($query)
    {
        return Http::get("{$this->baseUrl}/food/ingredients/autocomplete", [
            'apiKey' => $this->apiKey,
            'query' => $query,
        ])->json();
    }

    // Add more methods as needed, based on GitHub link
}
