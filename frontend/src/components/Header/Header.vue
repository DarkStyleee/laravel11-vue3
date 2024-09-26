<!-- eslint-disable vue/multi-word-component-names -->
<template>
  <header class="app-header">
    <div class="logo">
      <router-link to="/">Блог</router-link>
    </div>
    <nav class="nav-links">
      <router-link v-if="isAuthenticated" to="/" exact-active-class="active-link"
        >Главная</router-link
      >
      <router-link v-if="isAuthenticated" to="/posts" exact-active-class="active-link"
        >Посты</router-link
      >
      <router-link v-if="isAuthenticated" to="/users" exact-active-class="active-link"
        >Пользователи</router-link
      >
      <router-link v-if="isAuthenticated" to="/profile" exact-active-class="active-link"
        >Профиль</router-link
      >
      <router-link v-if="!isAuthenticated" to="/register" exact-active-class="active-link"
        >Регистрация</router-link
      >
      <router-link v-if="!isAuthenticated" to="/login" exact-active-class="active-link"
        >Вход</router-link
      >
      <button v-if="isAuthenticated" @click="logout">Выход</button>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isAuthenticated = computed(() => !!authStore.token);

const logout = () => {
  authStore.logout();
  router.push('/login');
};
</script>

<style lang="scss" scoped>
.app-header {
  width: 100%;
  background: #ffffff;
  padding: 15px 30px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 2;

  .logo {
    font-size: 1.5rem;
    font-weight: bold;

    a {
      color: #3b82f6;
      text-decoration: none;
      transition: color 0.3s;

      &:hover {
        color: #2563eb;
      }
    }
  }

  .nav-links {
    display: flex;
    gap: 20px;

    a,
    button {
      font-size: 1rem;
      color: #374151;
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 5px;
      transition:
        background 0.3s,
        color 0.3s;

      &.active-link {
        background: #3b82f6;
        color: #ffffff;
      }

      &:hover {
        background: #e5e7eb;
        color: #1f2937;
      }
    }

    button {
      background: none;
      border: none;
      cursor: pointer;
    }
  }
}
</style>
