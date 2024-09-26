<template>
  <div class="users-list-page">
    <h1 class="page-title">Список Пользователей</h1>
    <Loader v-if="loading" />
    <div v-else class="users-grid">
      <div v-for="user in users" :key="user.id" class="user-card">
        <div class="user-avatar">
          <img
            :src="user.avatar ? user.avatar : `https://i.pravatar.cc/150?img=${user.id}`"
            alt="Аватар пользователя"
          />
        </div>
        <div class="user-info">
          <h2>{{ user.name }}</h2>
          <p>{{ user.email }}</p>
          <router-link :to="`/user/${user.id}`" class="view-profile"
            >Просмотреть профиль</router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import api from '@/api';
import Loader from '@/components/Loader';
import ProfileAdapter from '@/adapters/ProfileAdapter';

const users = ref<ProfileAdapter[]>([]);
const loading = ref<boolean>(true);

const fetchUsersList = async () => {
  try {
    const res = await api.get('/users');
    users.value = res.data;
  } catch (error) {
    console.error('Ошибка при загрузке списка пользователей:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchUsersList();
});
</script>

<style lang="scss" scoped>
.users-list-page {
  padding: 20px;

  .page-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
    color: #1f2937;
  }

  .users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;

    .user-card {
      padding: 10px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      transition:
        transform 0.3s,
        box-shadow 0.3s;

      &:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
      }

      .user-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 150px;
        overflow: hidden;

        img {
          border-radius: 50%;
        }
      }

      .user-info {
        padding: 15px;
        text-align: center;

        h2 {
          font-size: 1.5rem;
          margin-bottom: 10px;
          color: #3b82f6;
        }

        p {
          font-size: 1rem;
          color: #6b7280;
          margin-bottom: 15px;
        }

        .view-profile {
          display: inline-block;
          padding: 8px 16px;
          background: #3b82f6;
          color: #ffffff;
          border-radius: 20px;
          text-decoration: none;
          transition: background 0.3s;

          &:hover {
            background: #2563eb;
          }
        }
      }
    }
  }
}
</style>
