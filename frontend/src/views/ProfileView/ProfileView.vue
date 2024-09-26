<template>
  <div class="profile-container">
    <div class="profile-header">
      <img v-if="user?.avatar" :src="user.avatar" alt="Аватар" class="avatar" />
      <div class="user-info">
        <h2>{{ user?.name }}</h2>
        <p>{{ user?.email }}</p>
      </div>
    </div>
    <div class="profile-details">
      <p v-if="user?.bio"><strong>Биография:</strong> {{ user.bio }}</p>
      <p v-if="user?.website">
        <strong>Сайт:</strong>
        <a :href="user.website" target="_blank">{{ user.website }}</a>
      </p>
    </div>
    <div class="profile-actions">
      <router-link :to="{ name: 'editProfile' }" class="button edit-button">
        Редактировать профиль
      </router-link>
      <button @click="logout" class="button logout-button">Выйти</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { onMounted, computed } from 'vue';

const authStore = useAuthStore();

const user = computed(() => authStore.user);

onMounted(() => {
  authStore.getUser();
});

const logout = () => {
  authStore.logout();
};
</script>

<style scoped>
.profile-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #fff;
}

.profile-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 20px;
}

.user-info h2 {
  margin: 0;
  font-size: 24px;
}

.user-info p {
  margin: 4px 0 0;
  color: #666;
}

.profile-details p {
  margin: 10px 0;
}

.profile-details a {
  color: #3490dc;
  text-decoration: none;
}

.profile-details a:hover {
  text-decoration: underline;
}

.profile-actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}

.button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  color: #fff;
  font-size: 16px;
}

.edit-button {
  background-color: #4caf50;
}

.edit-button:hover {
  background-color: #45a049;
}

.logout-button {
  background-color: #f44336;
}

.logout-button:hover {
  background-color: #da190b;
}
</style>
