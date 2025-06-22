<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';

const users = ref([]);
const loading = ref(true);
const errorMessage = ref('');
const authStore = useAuthStore();

async function fetchUsers() {
  try {
    loading.value = true;
    const response = await api.get('/admin/users');
    users.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'An error occurred fetching users.';
  } finally {
    loading.value = false;
  }
}

async function deleteUser(userId) {
  if (userId === authStore.user?.user_id) {
    alert("You cannot delete yourself.");
    return;
  }

  if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
    try {
      await api.delete(`/admin/users/${userId}`);
      // Remove the user from the local array to update the UI instantly
      users.value = users.value.filter(user => user.user_id !== userId);
    } catch (error) {
      alert("Failed to delete user. Please try again.");
      console.error(error);
    }
  }
}

onMounted(fetchUsers);
</script>

<template>
  <div class="container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Admin Panel - All Users</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && !errorMessage" class="table-responsive">
      <table class="table table-striped mt-2">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.user_id">
            <td>{{ user.user_id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <span :class="`badge bg-${user.role === 'admin' ? 'success' : 'secondary'}`">
                {{ user.role }}
              </span>
            </td>
            <td>{{ new Date(user.created_at).toLocaleDateString() }}</td>
            <td>
              <button v-if="user.user_id !== authStore.user?.user_id"
                class="btn btn-danger btn-sm"
                @click="deleteUser(user.user_id)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
