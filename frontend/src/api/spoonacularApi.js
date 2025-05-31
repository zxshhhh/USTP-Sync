import axios from 'axios';

const token = localStorage.getItem('token');

const API = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
});

export const searchRecipes = (query) =>
  API.get(`/recipes/search`, { params: { query } });

export const getRecipe = (id) =>
  API.get(`/recipes/${id}`, { params: { includeNutrition: true } });

export const autocompleteIngredients = (query) =>
  API.get(`/ingredients/autocomplete`, { params: { query } });
