<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const workoutDate = ref(new Date().toISOString().slice(0, 10));
const exercises = ref([]);
const allAvailableExercises = ref([]);
const selectedNewExercise = ref(null);

const loading = ref(true);
const errorMessage = ref('');
const successMessage = ref('');

const userId = authStore.user?.user_id;

async function fetchAllExercises() {
  if (!userId) {
    errorMessage.value = "User not found.";
    loading.value = false;
    return;
  }
  try {
    loading.value = true;
    const response = await api.get(`/exercises/all/user/${userId}`);
    allAvailableExercises.value = response.data;
  } catch (error) {
    errorMessage.value = "Failed to load exercises.";
  } finally {
    loading.value = false;
  }
}

function addExerciseToWorkout() {
  if (selectedNewExercise.value) {
    const exerciseToAdd = allAvailableExercises.value.find(ex => ex.id === selectedNewExercise.value.id && ex.type === selectedNewExercise.value.type);
    if (exerciseToAdd && !exercises.value.some(e => e.exercise_name === exerciseToAdd.name)) {
      exercises.value.push({
        exercise_id: exerciseToAdd.type === 'standard' ? exerciseToAdd.id : null,
        custom_exercise_id: exerciseToAdd.type === 'custom' ? exerciseToAdd.id : null,
        exercise_name: exerciseToAdd.name,
        sets: 3,
        reps: 10,
        weight: 20
      });
    }
  }
  selectedNewExercise.value = null;
}

function removeExercise(exerciseName) {
  exercises.value = exercises.value.filter(ex => ex.exercise_name !== exerciseName);
}

async function handleAddWorkout() {
  try {
    successMessage.value = '';
    errorMessage.value = '';

    const now = new Date();
    const time = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}`;

    const payload = {
      user_id: userId,
      workout_date: `${workoutDate.value} ${time}`,
      exercises: exercises.value.map(ex => ({
        exercise_id: ex.exercise_id,
        custom_exercise_id: ex.custom_exercise_id,
        sets: ex.sets,
        reps: ex.reps,
        weight: ex.weight
      }))
    };
    
    await api.post(`/workouts`, payload);
    successMessage.value = "Workout added successfully!";
    setTimeout(() => router.push('/dashboard'), 1500);

  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "Failed to add workout.";
  }
}

onMounted(fetchAllExercises);
</script>

<template>
  <div class="container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Log New Workout</h1>
    
    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

    <form @submit.prevent="handleAddWorkout" v-if="!loading">
      <div class="mb-4">
        <label for="workoutDate" class="form-label fs-5">Workout Date</label>
        <input type="date" class="form-control" id="workoutDate" v-model="workoutDate">
      </div>

      <h2 class="fs-4 mt-5 mb-3">Exercises in this Workout</h2>
      <div v-if="exercises.length > 0">
        <div v-for="(exercise, index) in exercises" :key="index" class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">{{ exercise.exercise_name }}</h5>
            <div class="row g-3 align-items-center">
              <div class="col-auto">
                <label class="form-label">Sets</label>
                <input type="number" class="form-control" v-model.number="exercise.sets">
              </div>
              <div class="col-auto">
                <label class="form-label">Reps</label>
                <input type="number" class="form-control" v-model.number="exercise.reps">
              </div>
              <div class="col-auto">
                <label class="form-label">Weight (kg)</label>
                <input type="number" step="0.5" class="form-control" v-model.number="exercise.weight">
              </div>
              <div class="col">
                <button type="button" @click="removeExercise(exercise.exercise_name)" class="btn btn-outline-danger mt-4 float-end">Remove</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p v-else>No exercises added yet. Select one below to get started.</p>

      <h2 class="fs-4 mt-5 mb-3">Add Exercise</h2>
      <div class="input-group">
        <select class="form-select" v-model="selectedNewExercise">
          <option :value="null" disabled>-- Select an exercise to add --</option>
          <optgroup label="Standard Exercises">
            <option v-for="ex in allAvailableExercises.filter(e => e.type === 'standard')" :key="ex.id" :value="ex">{{ ex.name }}</option>
          </optgroup>
          <optgroup label="Custom Exercises">
            <option v-for="ex in allAvailableExercises.filter(e => e.type === 'custom')" :key="ex.id" :value="ex">{{ ex.name }}</option>
          </optgroup>
        </select>
        <button @click="addExerciseToWorkout" type="button" class="btn btn-secondary">Add to Workout</button>
      </div>

      <div class="d-grid gap-2 mt-5">
        <button type="submit" class="btn btn-primary btn-lg">Save Workout</button>
      </div>
    </form>
  </div>
</template>
