import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api/spoonacular'; // Adjust if your Laravel backend is on a different URL

const api = {
    // Recipes
    searchRecipes: async (query, cuisine, diet, number = 10) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/recipes/search`, {
                params: { query, cuisine, diet, number }
            });
            return response.data;
        } catch (error) {
            console.error('Error searching recipes:', error);
            throw error;
        }
    },
    getRecipeInformation: async (id) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/recipes/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching recipe ${id} information:`, error);
            throw error;
        }
    },

    // Ingredients
    searchIngredients: async (query, number = 10) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/ingredients/search`, {
                params: { query, number }
            });
            return response.data;
        } catch (error) {
            console.error('Error searching ingredients:', error);
            throw error;
        }
    },
    getIngredientInformation: async (id) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/ingredients/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching ingredient ${id} information:`, error);
            throw error;
        }
    },

    // Products
    searchProducts: async (query, number = 10) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/products/search`, {
                params: { query, number }
            });
            return response.data;
        } catch (error) {
            console.error('Error searching products:', error);
            throw error;
        }
    },
    getProductInformation: async (id) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/products/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching product ${id} information:`, error);
            throw error;
        }
    },

    // Menu Items
    searchMenuItems: async (query, number = 10) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/menu-items/search`, {
                params: { query, number }
            });
            return response.data;
        } catch (error) {
            console.error('Error searching menu items:', error);
            throw error;
        }
    },
    getMenuItemInformation: async (id) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/menu-items/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching menu item ${id} information:`, error);
            throw error;
        }
    },
};

export default api;