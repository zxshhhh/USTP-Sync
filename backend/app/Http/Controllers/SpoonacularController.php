<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpoonacularService;

class SpoonacularController extends Controller
{
    protected $spoonacular;

    public function __construct(SpoonacularService $spoonacular)
    {
        $this->spoonacular = $spoonacular;
    }

    public function search(Request $request)
    {
        $query = $request->query('query', '');
        return response()->json($this->spoonacular->searchRecipes($query));
    }

    public function getRecipe($id)
    {
        return response()->json($this->spoonacular->getRecipeInformation($id));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->query('query', '');
        return response()->json($this->spoonacular->autocompleteIngredients($query));
    }
}
