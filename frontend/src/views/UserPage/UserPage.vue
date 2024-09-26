<template>
  <div class="user-page">
    <div v-if="loading" class="loader-wrapper">
      <Loader />
    </div>

    <div v-else-if="user" class="user-card">
      <div class="user-header">
        <div class="user-avatar">
          <img :src="`https://i.pravatar.cc/150?img=${user.id}`" alt="Аватар пользователя" />
        </div>
        <div class="user-info">
          <h1 class="user-name">{{ user.name }}</h1>
          <p class="user-email">{{ user.email }}</p>
        </div>
      </div>
      <div class="user-posts">
        <h2 class="posts-title">Посты пользователя</h2>

        <Loader v-if="postsLoading" />

        <div v-else-if="posts && posts.length" class="posts-grid">
          <PostCard v-for="post in posts" :key="post.id" :post="post" />
        </div>
        <div v-else class="no-posts">
          <p>У пользователя нет постов.</p>
        </div>
      </div>
    </div>

    <div v-else class="no-user">
      <p>Пользователь не найден.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import api from '@/api';
import { useRoute } from 'vue-router';
import Loader from '@/components/Loader';
import PostCard from '@/components/PostCard';
import type { User } from '@/@interfaces/User';
import PostAdapter from '@/adapters/PostAdapter';

const route = useRoute();
const user = ref<User | null>(null);
const posts = ref<PostAdapter[] | null>(null);
const loading = ref<boolean>(true);
const postsLoading = ref<boolean>(true);

const getUser = async () => {
  try {
    const res = await api.get(`/users/${route.params.id}`);
    user.value = res.data;
  } catch (error) {
    console.error('Ошибка при загрузке пользователя:', error);
  } finally {
    loading.value = false;
  }
};

const getUserPosts = async () => {
  try {
    const res = await api.get(`/users/${route.params.id}/posts`);
    posts.value = Array.isArray(res.data) ? res.data.map((post) => PostAdapter.fromRaw(post)) : [];
  } catch (error) {
    console.error('Ошибка при загрузке постов пользователя:', error);
  } finally {
    postsLoading.value = false;
  }
};

getUser();
getUserPosts();
</script>

<style lang="scss" scoped>
.user-page {
  padding: 40px 20px;
  background: #f0f4f8;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;

  .loader-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .user-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 1000px;
    width: 100%;
    transition:
      transform 0.3s,
      box-shadow 0.3s;

    &:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
    }

    .user-header {
      display: flex;
      align-items: center;
      margin-bottom: 30px;

      .user-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 30px;
        flex-shrink: 0;

        img {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
      }

      .user-info {
        .user-name {
          font-size: 2.5rem;
          color: #1f2937;
          margin: 0;
        }

        .user-email {
          font-size: 1.2rem;
          color: #6b7280;
          margin-top: 10px;
        }
      }
    }

    .user-posts {
      .posts-title {
        font-size: 2rem;
        color: #1f2937;
        margin-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 10px;
      }

      .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
      }

      .no-posts {
        text-align: center;
        font-size: 1.2rem;
        color: #9ca3af;
        margin-top: 10px;
      }
    }
  }

  .no-user {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 600px;
    width: 100%;
    text-align: center;
    font-size: 1.5rem;
    color: #6b7280;
  }
}
</style>
