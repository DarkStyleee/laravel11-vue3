<template>
  <div class="posts-list-page">
    <h1 class="page-title">Список Постов</h1>
    <Loader v-if="loading" />
    <div v-else class="posts-grid">
      <div v-for="post in posts" :key="post.id" class="post-card">
        <router-link :to="`/post/${post.id}`" class="post-link">
          <div class="post-header">
            <div class="post-author">
              <img
                :src="`https://i.pravatar.cc/50?img=${post.userId}`"
                alt="Аватар автора"
                class="author-avatar"
              />
              <span class="author-name">{{ post.user.name }}</span>
            </div>
            <div class="post-dates">
              <span class="created-at"
                >Создано: {{ dayjs(post.createdAt).format('DD.MM.YYYY HH:mm') }}</span
              >
              <span class="updated-at"
                >Обновлено: {{ dayjs(post.updatedAt).format('DD.MM.YYYY HH:mm') }}</span
              >
            </div>
          </div>
          <h2 class="post-title">{{ post.title }}</h2>
          <p class="post-excerpt">{{ getExcerpt(post.content) }}</p>
          <div class="post-footer">
            <span class="comments-count">
              <ChatBubbleLeftRightIcon class="icon" />
              {{ post.commentsCount }}
            </span>
            <span class="views-count">
              <EyeIcon class="icon" />
              {{ post.views }}
            </span>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { usePostStore } from '@/stores/post';
import Loader from '@/components/Loader';
import { ChatBubbleLeftRightIcon, EyeIcon } from '@heroicons/vue/24/solid';
import dayjs from 'dayjs';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
const postStore = usePostStore();
const { posts, loading } = storeToRefs(postStore);

onMounted(() => {
  postStore.fetchPosts();
});

const getExcerpt = (content: string): string => {
  return content.length > 100 ? content.substring(0, 100) + '...' : content;
};
</script>

<style lang="scss" scoped>
.posts-list-page {
  padding: 20px;

  .page-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
    color: #1f2937;
  }

  .posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;

    .post-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 20px;
      transition:
        transform 0.3s,
        box-shadow 0.3s;
      display: flex;
      flex-direction: column;
      justify-content: space-between;

      &:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
      }

      .post-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;

        .post-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 10px;
          gap: 10px;

          .post-author {
            display: flex;
            align-items: center;

            .author-avatar {
              width: 30px;
              height: 30px;
              border-radius: 50%;
              object-fit: cover;
              margin-right: 10px;
            }

            .author-name {
              font-size: 0.9rem;
              color: #4b5563;
            }
          }

          .post-dates {
            display: flex;
            flex-direction: column;
            font-size: 0.8rem;
            color: #9ca3af;

            .created-at,
            .updated-at {
              margin: 0;
            }
          }
        }

        .post-title {
          font-size: 1.5rem;
          margin: 10px 0;
          color: #3b82f6;
          transition: color 0.3s;

          &:hover {
            color: #2563eb;
          }
        }

        .post-excerpt {
          font-size: 1rem;
          color: #6b7280;
          flex-grow: 1;
        }

        .post-footer {
          display: flex;
          justify-content: flex-start;
          gap: 15px;
          margin-top: 15px;
          font-size: 0.9rem;
          color: #9ca3af;

          .comments-count,
          .views-count {
            display: flex;
            align-items: center;
          }

          .comments-count .icon,
          .views-count .icon {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            color: #3b82f6;
          }
        }
      }
    }
  }
}
</style>
