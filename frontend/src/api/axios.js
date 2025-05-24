import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: 'http://localhost:8000/api', // Laravel backend API
  withCredentials: true, // if using cookies/sessions
});

export default axiosInstance;
