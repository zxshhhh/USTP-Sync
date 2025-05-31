import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Your Laravel API URL
  headers: {
    'Content-Type': 'application/json',
  },
});

// Automatically inject token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token'); // Store your token after login
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
