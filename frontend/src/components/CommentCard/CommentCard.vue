<template>
  <div class="comment-card">
    <div class="comment-header">
      <span class="comment-author">{{ comment.user?.name }}</span>
      <span class="comment-date">{{ formattedDate }}</span>
    </div>
    <div class="comment-body">
      <p>{{ comment.content }}</p>
    </div>
    <div class="comment-actions">
      <button @click="toggleReplyForm" class="reply-button">Ответить</button>
    </div>
    <div v-if="showReplyForm" class="reply-form">
      <textarea v-model="replyContent" placeholder="Ваш ответ" rows="3"></textarea>
      <div class="reply-form-buttons">
        <button @click="submitReply" :disabled="loading">
          {{ loading ? 'Отправка...' : 'Отправить' }}
        </button>
        <button @click="toggleReplyForm" class="cancel-button">Отмена</button>
      </div>
    </div>
    <div v-if="comment.children && comment.children.length" class="children-comments">
      <CommentCard v-for="child in comment.children" :key="child.id" :comment="child" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import dayjs from 'dayjs';
import { usePostStore } from '@/stores/post';
import { useAuthStore } from '@/stores/auth';
import CommentAdapter from '@/adapters/CommentAdapter';
import { toast } from 'vue3-toastify';
const props = defineProps<{
  comment: CommentAdapter;
}>();

const showReplyForm = ref(false);
const replyContent = ref('');
const loading = ref(false);

const postStore = usePostStore();
const authStore = useAuthStore();

const formattedDate = computed(() => {
  return dayjs(props.comment.createdAt).format('DD.MM.YYYY HH:mm');
});

const toggleReplyForm = () => {
  showReplyForm.value = !showReplyForm.value;
};

const submitReply = async () => {
  if (!replyContent.value.trim()) return;

  loading.value = true;
  try {
    await postStore.addReply(
      replyContent.value,
      Number(authStore.user.id),
      Number(props.comment.postId),
      Number(props.comment.id)
    );
    replyContent.value = '';
    showReplyForm.value = false;
    toast.success('Ответ добавлен!');
  } catch (error) {
    console.error('Ошибка при отправке ответа:', error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped lang="scss">
.comment-card {
  background: #00000005;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 5px;

  .comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: #374151;
    margin-bottom: 10px;

    .comment-author {
      font-size: 1rem;
    }

    .comment-date {
      font-size: 0.875rem;
      color: #6b7280;
    }
  }

  .comment-body {
    font-size: 1rem;
    color: #4b5563;
    line-height: 1.5;
    margin-bottom: 10px;
  }

  .comment-actions {
    display: flex;
    gap: 10px;

    .reply-button {
      background: none;
      border: none;
      color: #3b82f6;
      cursor: pointer;
      font-size: 0.9rem;
      padding: 0;
      transition: color 0.3s;

      &:hover {
        color: #2563eb;
      }
    }
  }

  .reply-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;

    textarea {
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      resize: vertical;
      font-size: 1rem;
      font-family: inherit;
    }

    .reply-form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;

      button {
        padding: 8px 16px;
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;

        &:hover {
          background: #059669;
        }

        &:disabled {
          background: #6ee7b7;
          cursor: not-allowed;
        }
      }

      .cancel-button {
        background: #ef4444;

        &:hover {
          background: #dc2626;
        }
      }
    }
  }

  .children-comments {
    margin-left: 20px;
    margin-top: 10px;
    border-left: 2px solid #1271ff;
    padding-left: 10px;
    border-radius: 8px;
  }
}
</style>
