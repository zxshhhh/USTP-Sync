<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenFoodController extends Controller
{
    public function searchProduct(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['error' => 'Search query required'], 400);
        }

        $response = Http::get("https://world.openfoodfacts.org/cgi/search.pl", [
            'search_terms' => $query,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], 500);
    }

    public function getProductByBarcode($barcode)
    {
        $response = Http::get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");

        if ($response->successful()) {
            $data = $response->json();

            if ($data['status'] == 1) {
                // ✅ Step 6: Format only the fields you need
                return response()->json([
                    'product_name' => $data['product']['product_name'] ?? 'N/A',
                    'brands' => $data['product']['brands'] ?? 'N/A',
                    'nutriments' => $data['product']['nutriments'] ?? [],
                    'ingredients_text' => $data['product']['ingredients_text'] ?? '',
                    'image_url' => $data['product']['image_url'] ?? null,
                    'categories' => $data['product']['categories_tags'] ?? [],
                ]);
            } else {
                return response()->json(['error' => 'Product not found'], 404);
            }
        }

        return response()->json(['error' => 'Failed to fetch data'], 500);
    }
}
