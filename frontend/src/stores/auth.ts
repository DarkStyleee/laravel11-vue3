import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/api';
import { useRouter } from 'vue-router';
import ProfileAdapter from '@/adapters/ProfileAdapter';

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('authToken'));
  const user = ref<ProfileAdapter>(ProfileAdapter.fromRaw({}));
  const loading = ref(false);
  const error = ref<string | null>(null);
  const router = useRouter();

  // Получение текущего пользователя
  const getUser = async () => {
    if (token.value) {
      try {
        const response = await api.get('/user');
        user.value = ProfileAdapter.fromRaw(response.data['data']);
      } catch (error) {
        console.error('Ошибка получения пользователя:', error);
        logout();
      }
    }
  };

  const updateUser = async (data: ProfileAdapter) => {
    if (!token.value) return;
    try {
      loading.value = true;
      const response = await api.put('/profile', ProfileAdapter.toRaw(data));
      user.value = ProfileAdapter.fromRaw(response.data);
      error.value = null;
    } catch (err: any) {
      console.error('Ошибка обновления профиля:', err);
      error.value = err.response?.data?.message || 'Не удалось обновить профиль.';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const setToken = (newToken: string) => {
    token.value = newToken;
    localStorage.setItem('authToken', newToken);
    getUser();
  };

  const logout = () => {
    token.value = null;
    user.value = ProfileAdapter.fromRaw({});
    localStorage.removeItem('authToken');
    router.push('/login');
  };

  // Автоматическое получение пользователя при инициализации
  if (token.value) {
    getUser();
  }

  return {
    token,
    user,
    setToken,
    logout,
    getUser,
    updateUser,
    loading,
    error,
  };
});
