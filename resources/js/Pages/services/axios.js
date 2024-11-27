import axios from 'axios';

// Create a new Axios instance
const axiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',  // Your base API URL
    headers: {
        'Content-Type': 'application/json',
    },
});

// Add Authorization token to headers (for all requests)
axiosInstance.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default axiosInstance;
