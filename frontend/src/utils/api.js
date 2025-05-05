import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  withCredentials: true, 
  withXSRFToken :true,
});

// Automatically attach Bearer token if exists
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  async error => {
    // If CSRF token expired (Laravel returns 419)
    if (error.response && error.response.status === 419) {
      await api.get('sanctum/csrf-cookie');
      return api.request(error.config); // retry original request
    }
    return Promise.reject(error);
  }
);



export default api;
