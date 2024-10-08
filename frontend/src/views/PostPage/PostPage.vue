<template>
  <div class="post-page">
    <div v-if="loading" class="loader-wrapper">
      <Loader />
    </div>

    <div v-else-if="post" class="post-container">
      <div class="post-card">
        <div class="post-header">
          <h1 class="post-title">{{ post.title }}</h1>
          <div class="post-meta">
            <span class="post-author"
              >Автор: {{ post.user ? post.user.name : 'Неизвестный автор' }}</span
            >
            <span class="post-date"
              >Создано: {{ new Date(post.createdAt).toLocaleDateString() }}</span
            >
            <span class="post-date"
              >Обновлено: {{ new Date(post.updatedAt).toLocaleDateString() }}</span
            >
            <span class="post-views">Просмотры: {{ post.views }}</span>
          </div>
        </div>
        <div class="post-body">
          <p>{{ post.content }}</p>
        </div>
        <div class="post-btns">
          <VoteButtons
            v-if="post.id"
            :postId="post.id"
            :initialLikes="post.likes"
            :initialDislikes="post.dislikes"
            :initialUserVote="post.userVote"
          />

          <button @click="toggleComments" class="toggle-comments">
            {{ commentsVisible ? 'Скрыть комментарии' : 'Показать комментарии' }}
          </button>
        </div>
      </div>

      <div v-if="commentsLoading" class="loader-wrapper">
        <Loader />
      </div>

      <div v-else-if="commentsVisible" class="comments-section">
        <h2 class="comments-title">Комментарии</h2>
        <CommentForm :postId="Number(post.id)" />
        <div v-if="comments && comments.length" class="comments-list">
          <CommentCard v-for="comment in comments" :key="comment.id" :comment="comment" />
        </div>
        <div v-else class="no-comments">
          <p>Нет комментариев для этого поста.</p>
        </div>
      </div>
    </div>

    <div v-else class="no-post">
      <p>Пост не найден.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { usePostStore } from '@/stores/post';
import Loader from '@/components/Loader';
import CommentCard from '@/components/CommentCard';
import CommentForm from '@/components/CommentForm';
import { storeToRefs } from 'pinia';
import VoteButtons from '@/components/VoteButtons';

const route = useRoute();
const postStore = usePostStore();

const commentsVisible = ref<boolean>(false);

const { post, comments, loading, commentsLoading } = storeToRefs(postStore);

const toggleComments = () => {
  commentsVisible.value = !commentsVisible.value;
  if (commentsVisible.value) {
    postStore.fetchComments(Number(post.value.id));
  }
};

onMounted(() => {
  postStore.fetchPost(Number(route.params.id));
});
</script>

<style lang="scss" scoped>
.post-page {
  padding: 40px 20px;
  background: #f9fafb;
  display: flex;
  justify-content: center;
  align-items: flex-start;

  .loader-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .post-container {
    width: 100%;
    max-width: 800px;
  }

  .post-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
  }

  .post-header {
    margin-bottom: 20px;
  }

  .post-title {
    font-size: 2rem;
    color: #1f2937;
  }

  .post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 0.875rem;
    color: #6b7280;

    .post-author,
    .post-date,
    .post-views {
      background: #e5e7eb;
      padding: 5px 10px;
      border-radius: 4px;
    }
  }

  .post-body {
    font-size: 1.125rem;
    color: #4b5563;
    line-height: 1.6;
  }

  .post-btns {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
    gap: 10px;

    .toggle-comments {
      padding: 8px 16px;
      background: #3b82f6;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;

      &:hover {
        background: #2563eb;
      }
    }
  }

  .comments-section {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 20px 30px;
    display: flex;
    flex-direction: column;
    gap: 10px;

    .comments-title {
      font-size: 1.8rem;
      color: #1f2937;
      border-bottom: 2px solid #e5e7eb;
      padding-bottom: 10px;
    }

    .comments-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .no-comments {
      text-align: center;
      font-size: 1rem;
      color: #9ca3af;
    }
  }

  .no-post {
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
