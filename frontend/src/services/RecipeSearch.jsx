// src/components/RecipeSearch.js
import React, { useState }
from 'react';
import { searchRecipes, getRecipeDetails } from './SpoonacularApi'; // Adjust path as needed

function RecipeSearch() {
  const [query, setQuery] = useState('');
  const [recipes, setRecipes] = useState([]);
  const [selectedRecipe, setSelectedRecipe] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSearch = async (e) => {
    e.preventDefault();
    if (!query.trim()) return;

    setLoading(true);
    setError(null);
    setSelectedRecipe(null); // Clear previous selection
    setRecipes([]); // Clear previous results

    try {
      // Example of passing additional options supported by Spoonacular
      const params = {
        // cuisine: 'Italian',
        // number: 5 // Max number of results
      };
      const data = await searchRecipes(query, params);
      setRecipes(data.results || []); // Spoonacular's search result is often in `data.results`
    } catch (err) {
      setError(err.error || 'Failed to fetch recipes. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const fetchRecipeDetails = async (id) => {
    setLoading(true);
    setError(null);
    try {
      // Example: include nutrition information
      const data = await getRecipeDetails(id, { includeNutrition: true });
      setSelectedRecipe(data);
    } catch (err) {
      setError(err.error || `Failed to fetch recipe details for ID ${id}.`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1>Recipe Search 🍳</h1>
      <form onSubmit={handleSearch}>
        <input
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="Search for recipes (e.g., pasta)"
          disabled={loading}
        />
        <button type="submit" disabled={loading}>
          {loading ? 'Searching...' : 'Search'}
        </button>
      </form>

      {error && <p style={{ color: 'red' }}>Error: {error}</p>}

      {loading && !recipes.length && !selectedRecipe && <p>Loading...</p>}

      {recipes.length > 0 && !selectedRecipe && (
        <div>
          <h2>Search Results:</h2>
          <ul>
            {recipes.map((recipe) => (
              <li key={recipe.id}>
                {recipe.title}
                <button onClick={() => fetchRecipeDetails(recipe.id)} disabled={loading}>
                  View Details
                </button>
              </li>
            ))}
          </ul>
        </div>
      )}

      {selectedRecipe && (
        <div>
          <button onClick={() => setSelectedRecipe(null)}>&larr; Back to results</button>
          <h2>{selectedRecipe.title}</h2>
          <img src={selectedRecipe.image} alt={selectedRecipe.title} width="200" />
          <p>Ready in: {selectedRecipe.readyInMinutes} minutes</p>
          <p>Servings: {selectedRecipe.servings}</p>
          <h3>Ingredients:</h3>
          <ul>
            {selectedRecipe.extendedIngredients?.map((ing) => (
              <li key={ing.id}>{ing.original}</li>
            ))}
          </ul>
          <h3>Instructions:</h3>
          <div dangerouslySetInnerHTML={{ __html: selectedRecipe.instructions }} />
          {/* Display more details as needed, e.g., nutrition */}
          {selectedRecipe.nutrition && (
             <div>
                 <h4>Nutrition (per serving):</h4>
                 {selectedRecipe.nutrition.nutrients?.find(n => n.name === 'Calories')?.amount} kcal
                 {/* Add more nutrients */}
             </div>
          )}
        </div>
      )}
    </div>
  );
}

export default RecipeSearch;