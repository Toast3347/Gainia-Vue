<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const workouts = ref([]);
const loading = ref(true);
const errorMessage = ref('');
const userName = authStore.user?.name || 'User';

async function fetchWorkouts() {
  const userId = authStore.user?.user_id;
  if (!userId) {
    errorMessage.value = "Could not identify user. Please log in again.";
    loading.value = false;
    return;
  }

  try {
    loading.value = true;
    const response = await api.get(`/workouts/user/${userId}`);
    workouts.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to load workouts.';
  } finally {
    loading.value = false;
  }
}

onMounted(fetchWorkouts);
</script>

<template>
  <div class="container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Welcome, {{ userName }}!</h1>
    <h2 class="text-center text-muted mb-5">Your Recent Workouts</h2>

    <div class="text-center mb-4">
      <router-link to="/workouts/add" class="btn btn-primary btn-lg">Log New Workout</router-link>
    </div>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && !errorMessage">
      <div v-if="workouts.length > 0" class="list-group w-75 mx-auto">
        <router-link
          v-for="workout in workouts" 
          :key="workout.workout_id"
          :to="`/workouts/${workout.workout_id}`"
          class="list-group-item list-group-item-action flex-column align-items-start"
        >
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Workout Session</h5>
            <small>{{ new Date(workout.workout_date).toLocaleDateString() }}</small>
          </div>
          <p class="mb-1">
            Logged on {{ new Date(workout.workout_date.replace(' ', 'T')).toLocaleTimeString() }}. Click to see details.
          </p>
        </router-link>
      </div>
      <div v-else class="text-center">
        <p class="lead">You haven't logged any workouts yet.</p>
        <a href="/workouts/add" class="btn btn-primary mt-3">Log Your First Workout</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
.list-group-item {
  margin-bottom: 1rem;
  border-radius: 0.5rem;
}
</style>