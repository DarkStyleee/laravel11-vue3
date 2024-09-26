<template>
  <div class="post-card">
    <router-link :to="`/post/${post.id}`" class="post-link">
      <div class="post-image-wrapper">
        <img :src="defaultImage" alt="Изображение поста" class="post-image" />
      </div>
      <div class="post-content">
        <span class="post-date">{{ formattedDate }}</span>
        <h4 class="post-title">{{ post.title }}</h4>
        <p class="post-excerpt">{{ excerpt }}</p>
        <div class="post-footer">
          <div class="post-stats">
            <span class="post-views">
              <!-- Иконка просмотров -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-views"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"
                />
              </svg>
              {{ post.views }}
            </span>
            <span class="post-comments">
              <!-- Иконка комментариев -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-comments"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.013 9.013 0 01-4.764-1.354L3 20l1.354-4.236A8.96 8.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                />
              </svg>
              {{ post.commentsCount }}
            </span>
          </div>
          <span class="post-read-more">Читать далее &raquo;</span>
        </div>
      </div>
    </router-link>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import dayjs from 'dayjs';
import type PostAdapter from '@/adapters/PostAdapter';

const props = defineProps<{
  post: PostAdapter;
}>();

const excerpt = computed(() => {
  return props.post.content.length > 100
    ? props.post.content.substring(0, 100) + '...'
    : props.post.content;
});

const formattedDate = computed(() => {
  return dayjs(props.post.createdAt).format('DD.MM.YYYY');
});

const defaultImage = 'https://via.placeholder.com/400x200?text=Нет+изображения';
</script>

<style lang="scss" scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

.post-card {
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  height: 100%;

  &:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  }

  .post-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;

    .post-image-wrapper {
      width: 100%;
      height: 200px;
      overflow: hidden;

      .post-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;

        &:hover {
          transform: scale(1.05);
        }
      }
    }

    .post-content {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex: 1;

      .post-date {
        font-size: 0.875rem;
        color: #9ca3af;
        margin-bottom: 10px;
      }

      .post-title {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        line-height: normal;
        color: #1f2937;
        margin-bottom: 10px;
        transition: color 0.3s ease;

        &:hover {
          color: #3b82f6;
        }
      }

      .post-excerpt {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #4b5563;
        flex: 1;
        margin-bottom: 15px;
      }

      .post-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .post-stats {
          display: flex;
          gap: 15px;

          .icon {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            vertical-align: middle;
          }

          .post-views,
          .post-comments {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6b7280;
          }
        }

        .post-read-more {
          font-size: 0.875rem;
          color: #3b82f6;
          font-weight: 500;
          transition: color 0.3s ease;

          &:hover {
            color: #1d4ed8;
            text-decoration: underline;
          }
        }
      }
    }
  }
}
</style>
