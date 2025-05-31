import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000', // update to your backend URL
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

// Attach token to all outgoing requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token'); // Get token from storage
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export default api;
