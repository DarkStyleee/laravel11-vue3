<template>
  <div class="home-page">
    <div class="welcome-section">
      <h1>Добро пожаловать в наше приложение!</h1>
      <p>Исследуйте пользователей и посты, используя навигационное меню сверху.</p>
    </div>

    <div class="statistics-section">
      <div class="stat-card">
        <h2>{{ usersCount }}</h2>
        <p>Пользователей</p>
      </div>
      <div class="stat-card">
        <h2>{{ postsCount }}</h2>
        <p>Постов</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue';
import api from '@/api';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const usersCount = ref<number>(0);
const postsCount = ref<number>(0);

const fetchStatistics = async () => {
  try {
    const [usersRes, postsRes] = await Promise.all([api.get('/users'), api.get('/posts')]);
    usersCount.value = usersRes.data.length;
    postsCount.value = postsRes.data.length;
  } catch (error) {
    console.error('Ошибка при загрузке статистики:', error);
  }
};

const authStore = useAuthStore();
const router = useRouter();

const isAuthenticated = computed(() => !!authStore.token);

onMounted(() => {
  if (!isAuthenticated.value) {
    router.push('/login');
  }

  fetchStatistics();
});
</script>

<style lang="scss" scoped>
.home-page {
  padding: 80px 20px 20px; /* Учитываем фиксированную шапку */
  max-width: 1200px;
  margin: 0 auto;
  background: #f0f4f8;

  .welcome-section {
    text-align: center;
    margin-bottom: 40px;

    h1 {
      font-size: 2.5rem;
      color: #1f2937;
      margin-bottom: 20px;
    }

    p {
      font-size: 1.2rem;
      color: #4b5563;
    }
  }

  .statistics-section {
    display: flex;
    justify-content: center;
    gap: 40px;

    .stat-card {
      min-width: 200px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 30px 20px;
      text-align: center;
      transition:
        transform 0.3s,
        box-shadow 0.3s;

      &:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
      }

      h2 {
        font-size: 2rem;
        color: #3b82f6;
        margin-bottom: 10px;
      }

      p {
        font-size: 1rem;
        color: #6b7280;
      }
    }
  }

  @media (max-width: 768px) {
    .statistics-section {
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }
  }
}
</style>
