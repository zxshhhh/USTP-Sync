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

export const getCurrentWeather = (city) => API.get(`/weather/current`, { params: { city } });
export const getForecast = (city) => API.get(`/weather/forecast`, { params: { city } });
