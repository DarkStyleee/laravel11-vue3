<template>
  <div class="edit-profile-container">
    <h2>Редактировать Профиль</h2>
    <form @submit.prevent="handleSubmit" class="edit-profile-form">
      <div class="form-group">
        <label for="name">Имя</label>
        <input type="text" id="name" v-model="form.name" required />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>
      <div class="form-group">
        <label for="avatar">Аватар URL</label>
        <input type="url" id="avatar" v-model="form.avatar" />
      </div>
      <div class="form-group">
        <label for="bio">Биография</label>
        <textarea id="bio" v-model="form.bio" rows="4"></textarea>
      </div>
      <div class="form-group">
        <label for="website">Сайт</label>
        <input type="url" id="website" v-model="form.website" />
      </div>
      <div class="form-group">
        <label for="password">Новый Пароль</label>
        <input type="password" id="password" v-model="form.password" />
      </div>
      <div class="form-group">
        <label for="password_confirmation">Подтверждение Пароля</label>
        <input type="password" id="password_confirmation" v-model="form.password_confirmation" />
      </div>
      <div class="form-actions">
        <button type="submit" class="button save-button" :disabled="authStore.loading">
          Сохранить
        </button>
        <router-link :to="{ name: 'profile' }" class="button cancel-button"> Отмена </router-link>
      </div>
      <div v-if="authStore.error" class="error-message">
        {{ authStore.error }}
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { RouteName } from '@/router/routes';

const authStore = useAuthStore();
const router = useRouter();

const form = reactive({
  name: '',
  email: '',
  avatar: '',
  bio: '',
  website: '',
  password: '',
  password_confirmation: ''
});

onMounted(() => {
  authStore.getUser().then(() => {
    if (authStore.user) {
      form.name = authStore.user.name;
      form.email = authStore.user.email;
      form.avatar = authStore.user.avatar || '';
      form.bio = authStore.user.bio || '';
      form.website = authStore.user.website || '';
    }
  });
});

const handleSubmit = async () => {
  try {
    await authStore.updateUser({
      name: form.name,
      email: form.email,
      avatar: form.avatar,
      bio: form.bio,
      website: form.website,
      password: form.password,
      passwordConfirmation: form.password_confirmation
    });
    router.push({ name: RouteName.PROFILE });
  } catch (error) {
    // Ошибка отображается через authStore.error
  }
};
</script>

<style scoped>
.edit-profile-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #fff;
}

.edit-profile-container h2 {
  text-align: center;
  margin-bottom: 20px;
}

.edit-profile-form .form-group {
  margin-bottom: 15px;
}

.edit-profile-form label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.edit-profile-form input,
.edit-profile-form textarea {
  border-radius: 4px;
  border: 1px solid #ddd;
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
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

.save-button {
  background-color: #4caf50;
}

.save-button:hover {
  background-color: #45a049;
}

.cancel-button {
  background-color: #9e9e9e;
}

.cancel-button:hover {
  background-color: #7e7e7e;
}

.error-message {
  margin-top: 15px;
  color: #f44336;
  text-align: center;
}
</style>
