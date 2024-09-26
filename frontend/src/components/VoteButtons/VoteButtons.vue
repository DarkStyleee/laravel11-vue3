<template>
  <div class="vote-buttons">
    <span :class="['vote-button', { active: userVote === 1 }]" class="vote-button-icon">
      <HandThumbUpIcon class="icon like" @click.prevent="toggleVote(userVote === 1 ? 0 : 1)" />
      {{ likes }}
    </span>
    <span :class="['vote-button', { active: userVote === -1 }]" class="vote-button-icon">
      <HandThumbDownIcon
        @click.prevent="toggleVote(userVote === -1 ? 0 : -1)"
        class="icon dislike"
      />
      {{ dislikes }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { defineProps } from 'vue';
import { usePostStore } from '@/stores/post';
import { HandThumbUpIcon, HandThumbDownIcon } from '@heroicons/vue/24/solid';
import { toast } from 'vue3-toastify';

const props = defineProps<{
  postId: number;
  initialLikes: number;
  initialDislikes: number;
  initialUserVote: number | null;
}>();

const postStore = usePostStore();

const likes = ref(props.initialLikes);
const dislikes = ref(props.initialDislikes);
const userVote = ref(props.initialUserVote);

const toggleVote = async (value: number) => {
  try {
    await postStore.votePost(props.postId, value);
    if (userVote.value === 1) likes.value--;
    if (userVote.value === -1) dislikes.value--;
    if (value === 1) likes.value++;
    if (value === -1) dislikes.value++;
    userVote.value = value;
    toast.success('Ваш голос учтен!');
  } catch (error) {
    console.error('Ошибка при голосовании:', error);
    toast.error('Ошибка при голосовании');
  }
};
</script>

<style scoped lang="scss">
.vote-buttons {
  display: flex;
  gap: 10px;

  .vote-button {
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background 0.3s;
  }

  .icon {
    width: 24px;
    height: 24px;
    cursor: pointer;
  }

  .like {
    color: #2ba72b;

    &:hover {
      color: #32c232;
    }
  }

  .dislike {
    color: #b73c3c;

    &:hover {
      color: #e24e4e;
    }
  }

  .vote-button-icon {
    gap: 5px;
  }
}
</style>
