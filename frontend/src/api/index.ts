import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { storeToRefs } from 'pinia';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

api.interceptors.request.use((config) => {
  const authStore = useAuthStore();
  const { token } = storeToRefs(authStore);
  if (token.value) {
    config.headers.Authorization = `Bearer ${token.value}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

export default api;
