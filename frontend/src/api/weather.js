import axios from 'axios';

const API = axios.create({
  baseURL: 'http://localhost:8000/api',
});

export const getCurrentWeather = (city) => API.get(`/weather/current`, { params: { city } });
export const getForecast = (city) => API.get(`/weather/forecast`, { params: { city } });
