<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';

const route = useRoute();
const exercises = ref([]);
const loading = ref(true);
const errorMessage = ref('');
const workoutId = route.params.id;

async function fetchWorkoutDetails() {
  try {
    loading.value = true;
    const response = await api.get(`/workouts/${workoutId}/exercises`);
    exercises.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to load workout details.';
  } finally {
    loading.value = false;
  }
}

onMounted(fetchWorkoutDetails);
</script>

<template>
  <div class="container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Workout Details</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && !errorMessage">
      <div v-if="exercises.length > 0" class="table-responsive w-75 mx-auto">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Exercise Name</th>
              <th>Muscle Group</th>
              <th>Sets</th>
              <th>Reps</th>
              <th>Weight (kg)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(exercise, index) in exercises" :key="index">
              <td>{{ exercise.exercise_name }}</td>
              <td><span class="badge bg-info">{{ exercise.muscle_group }}</span></td>
              <td>{{ exercise.sets }}</td>
              <td>{{ exercise.reps }}</td>
              <td>{{ exercise.weight }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-center">
        <p class="lead">No exercises were logged for this workout session.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table {
  margin-top: 20px;
  border-radius: 0.5rem;
  overflow: hidden;
}
</style>
