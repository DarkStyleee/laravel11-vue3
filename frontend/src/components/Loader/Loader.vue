<template>
  <div class="loader-container">
    <div class="spinner"></div>
  </div>
</template>

<script>
export default {
  name: 'AppLoader'
};
</script>

<style lang="scss" scoped>
.loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 0;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 6px solid #e5e7eb; /* Светлый серый цвет */
  border-top: 6px solid #3b82f6; /* Синий акцент */
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
