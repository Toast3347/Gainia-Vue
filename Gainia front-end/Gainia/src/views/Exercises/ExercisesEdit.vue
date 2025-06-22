<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/services/api';

const route = useRoute();
const router = useRouter();
const exercise = ref(null);
const loading = ref(true);
const errorMessage = ref('');
const successMessage = ref('');

const exerciseId = route.params.exerciseId;

async function fetchExerciseDetails() {
  try {
    loading.value = true;
    errorMessage.value = '';
    successMessage.value = '';
    const response = await api.get(`/exercises/details/${exerciseId}`);
    exercise.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to load exercise details.';
  } finally {
    loading.value = false;
  }
}

async function handleUpdate() {
  try {
    errorMessage.value = '';
    successMessage.value = '';
    await api.put(`/exercises/details/${exerciseId}`, exercise.value);
    successMessage.value = "Exercise updated successfully!";
    setTimeout(() => {
      router.push('/exercises');
    }, 1500);
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to update exercise.';
  }
}

onMounted(fetchExerciseDetails);
</script>

<template>
  <div class="container py-5">
    <h1 v-if="exercise" class="display-4 fw-bold text-center mb-4">Edit Exercise</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>
    
    <div v-if="successMessage" class="alert alert-success text-center">
      {{ successMessage }}
    </div>

    <form v-if="exercise" @submit.prevent="handleUpdate" class="w-75 mx-auto">
      <div class="mb-3">
        <label for="exerciseName" class="form-label">Exercise Name</label>
        <input
          type="text"
          class="form-control"
          id="exerciseName"
          v-model="exercise.name"
          required
        />
      </div>
      <div class="mb-3">
        <label for="muscleGroup" class="form-label">Muscle Group</label>
        <select class="form-select" id="muscleGroup" v-model="exercise.muscle_group" required>
            <option value="Abs">Abs</option>
            <option value="Back">Back</option>
            <option value="Biceps">Biceps</option>
            <option value="Cardio">Cardio</option>
            <option value="Chest">Chest</option>
            <option value="Legs">Legs</option>
            <option value="Shoulders">Shoulders</option>
            <option value="Triceps">Triceps</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea
          class="form-control"
          id="description"
          v-model="exercise.description"
          rows="4"
        ></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update Exercise</button>
    </form>
  </div>
</template>

<style scoped>
</style>