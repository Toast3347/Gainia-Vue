<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

const username = ref('');
const password = ref('');
const authStore = useAuthStore();

const handleLogin = async () => {
  if (username.value && password.value) {
    await authStore.login({ username: username.value, password: password.value });
  } else {
    alert('Please fill in both fields.');
  }
};
</script>

<template>
  <div class="container mt-5 min-vh-100">
    <h1 class="text-center mb-4">Login</h1>
    <form @submit.prevent="handleLogin" class="w-50 mx-auto">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" v-model="username" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" v-model="password" required>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="/create-account" class="btn btn-secondary">Create Account</a>
      </div>
    </form>
  </div>
</template>

