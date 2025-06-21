<script setup>
import { ref, onUnmounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { storeToRefs } from 'pinia';

const name = ref('');
const username = ref('');
const password = ref('');
const authStore = useAuthStore();
const { message } = storeToRefs(authStore);

const handleCreateAccount = async () => {
  if (username.value && password.value) {
    await authStore.register({ name: name.value, username: username.value, password: password.value });
  } else {
    authStore.setMessage('Please fill in all fields.', 'error');
  }
};

onUnmounted(() => {
    authStore.clearMessage();
});
</script>

<template>
  <div class="container mt-5 min-vh-100">
    <h1 class="text-center mb-4">Create Account</h1>
    <form @submit.prevent="handleCreateAccount" class="w-50 mx-auto">
      
      <div v-if="message.text" :class="`alert ${message.type === 'error' ? 'alert-danger' : 'alert-success'}`" role="alert">
        {{ message.text }}
      </div>

      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" v-model="name" required placeholder="For example: John Doe">
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Email</label>
        <input type="text" class="form-control" id="username" v-model="username" required placeholder="For example: johndoe@gmail.com">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" v-model="password" required>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Create Account</button>
        <a href="/login" class="btn btn-secondary">Back to Login</a>
      </div>
    </form>
  </div>
</template> 