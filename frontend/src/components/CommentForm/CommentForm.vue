<template>
  <div class="comment-form">
    <textarea v-model="content" placeholder="Напишите ваш комментарий..." rows="3"></textarea>
    <div class="form-actions">
      <button @click="submitComment" :disabled="loading">
        {{ loading ? 'Отправка...' : 'Отправить' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePostStore } from '@/stores/post';
import { useAuthStore } from '@/stores/auth';
import { toast } from 'vue3-toastify';
const props = defineProps<{
  postId: number;
}>();

const content = ref('');
const loading = ref(false);

const postStore = usePostStore();
const authStore = useAuthStore();

const submitComment = async () => {
  if (!content.value.trim()) return;

  loading.value = true;
  try {
    await postStore.addComment(content.value, Number(authStore.user.id), props.postId);
    toast.success('Комментарий добавлен!');
    content.value = '';
  } catch (error) {
    console.error('Ошибка при отправке комментария:', error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped lang="scss">
.comment-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;

  textarea {
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    resize: vertical;
    font-size: 1rem;
    font-family: inherit;
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;

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
  }
}
</style>
