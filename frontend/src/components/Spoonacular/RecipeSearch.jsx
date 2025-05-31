import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { searchRecipes, getRecipe } from '../../api/spoonacularApi';
import 'boxicons'
import './RecipeSearch.css'
import Background from '../../components/background_gradient'


export default function RecipeSearch() {
  const [query, setQuery] = useState('');
  const [recipes, setRecipes] = useState([]);
  const [selectedRecipe, setSelectedRecipe] = useState(null);
  const [ingredients, setIngredients] = useState([]);
  const [nutrients, setNutrients] = useState([]);

  const navigate = useNavigate();

  const handleClickMainPage = () => {
    navigate('/Mainpage');
  };

  const handleSearch = async () => {
    const response = await searchRecipes(query);
    const results = response.data.results;
    console.log("API response:", response.data);
  if (!query.trim()) {
      alert("Please enter a recipe name to search.");
      return;
    }

    try {
      const response = await searchRecipes(query);
      const results = response.data.results;
      console.log("API response:", response.data);

      setRecipes(results);

      if (results.length > 0) {
        const firstRecipeId = results[0].id;
        handleSelectRecipe(firstRecipeId); // auto-select first result
      } else {
        setSelectedRecipe(null);
        setIngredients([]);
      }
    } catch (error) {
      console.error("Error fetching recipes:", error.response?.data || error.message);
      alert("Something went wrong while searching. Please try again.");
    }
  };

  const handleSelectRecipe = async (recipeId) => {
    const response = await getRecipe(recipeId);
    console.log("Recipe details:", response.data);
    setSelectedRecipe(response.data);
    setIngredients(response.data.extendedIngredients || []);
    
    // Nutrients are inside response.data.nutrition.nutrients
    setNutrients(response.data.nutrition?.nutrients || []);
  };

  return (
    <main>
      <box-icon name='arrow-back' color='#ffffff' onClick={handleClickMainPage} className="arrow-icon"></box-icon>
      <Background />
      <section className='recipe-search-container'>
        <div className="search-content">
          <div className="search-container">
            <input
              type="text"
              placeholder="Search for recipes..."
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              className="search-input"
            />
            <button
              onClick={handleSearch}
              className="search-button"
            >
              Search
            </button>
          </div>

          <ul className="">
            {recipes.map((recipe) => (
              <li key={recipe.id} className="" onClick={() => handleSelectRecipe(recipe.id)}>
                <strong>{recipe.title}</strong>
              </li>
            ))}
          </ul>

          {selectedRecipe && (
            <div className="selected-recipe-container">
              <h2 className="Recipe-title">{selectedRecipe.title}</h2>
              <img src={selectedRecipe.image} alt={selectedRecipe.title} className="recipe-image" />
              <h3 className="Instructions">Instructions:</h3>
              <div className="intructions-paragraph"
                dangerouslySetInnerHTML={{ __html: selectedRecipe.instructions }}
              />
              <h3 className="Ingredients">Ingredients:</h3>
              <ul className="Ingredients-paragraph">
                {ingredients.map((ingredient) => (
                  <li key={ingredient.id}>
                    {ingredient.original}
                  </li>
                ))}
              </ul>
              <h3 className="Nutrition">Nutritional Information:</h3>
              <ul className="Nutrtion-paragraph">
                {selectedRecipe?.nutrition?.nutrients?.map((nutrient) => (
                  <li key={nutrient.name}>
                    {nutrient.name}: {nutrient.amount} {nutrient.unit}
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </section>
    </main>
  );
}