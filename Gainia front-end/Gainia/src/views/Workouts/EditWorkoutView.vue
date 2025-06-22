<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const workout = ref({ date: '', exercises: [] });
const allAvailableExercises = ref([]);
const selectedNewExercise = ref(null);

const loading = ref(true);
const errorMessage = ref('');
const successMessage = ref('');

const workoutId = route.params.id;
const userId = authStore.user?.user_id;

async function fetchData() {
  if (!userId) {
    errorMessage.value = "User not found.";
    loading.value = false;
    return;
  }
  try {
    loading.value = true;
    const [workoutDetailsRes, workoutExercisesRes, allExercisesRes] = await Promise.all([
      api.get(`/workouts/${workoutId}`),
      api.get(`/workouts/${workoutId}/exercises`),
      api.get(`/exercises/all/user/${userId}`)
    ]);

    workout.value.date = workoutDetailsRes.data.workout_date.split(' ')[0]; // Get just the YYYY-MM-DD part
    workout.value.exercises = workoutExercisesRes.data.map(ex => ({ ...ex, isCustom: !ex.exercise_id }));
    allAvailableExercises.value = allExercisesRes.data;

  } catch (error) {
    errorMessage.value = "Failed to load workout data.";
  } finally {
    loading.value = false;
  }
}

function addExerciseToWorkout() {
  const exerciseToAdd = allAvailableExercises.value.find(ex => ex.id === selectedNewExercise.value.id && ex.type === selectedNewExercise.value.type);
  if (exerciseToAdd && !workout.value.exercises.some(e => e.exercise_name === exerciseToAdd.name)) {
    workout.value.exercises.push({
      exercise_id: exerciseToAdd.type === 'standard' ? exerciseToAdd.id : null,
      custom_exercise_id: exerciseToAdd.type === 'custom' ? exerciseToAdd.id : null,
      exercise_name: exerciseToAdd.name,
      sets: 3,
      reps: 10,
      weight: 20
    });
  }
  selectedNewExercise.value = null;
}

function removeExercise(exerciseName) {
  workout.value.exercises = workout.value.exercises.filter(ex => ex.exercise_name !== exerciseName);
}

async function handleUpdateWorkout() {
  try {
    successMessage.value = '';
    errorMessage.value = '';

    const payload = {
      workout_date: workout.value.date,
      exercises: workout.value.exercises.map(ex => ({
        exercise_id: ex.exercise_id,
        custom_exercise_id: ex.custom_exercise_id,
        sets: ex.sets,
        reps: ex.reps,
        weight: ex.weight
      }))
    };
    
    await api.put(`/workouts/${workoutId}`, payload);
    successMessage.value = "Workout updated successfully!";
    setTimeout(() => router.push('/dashboard'), 1500);

  } catch (error) {
    errorMessage.value = "Failed to update workout.";
  }
}

onMounted(fetchData);
</script>

<template>
  <div class="container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Edit Workout</h1>
    
    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

    <div v-if="!loading && !errorMessage">
      <form @submit.prevent="handleUpdateWorkout">
        <div class="mb-4">
          <label for="workoutDate" class="form-label fs-5">Workout Date</label>
          <input type="date" class="form-control" id="workoutDate" v-model="workout.date">
        </div>

        <h2 class="fs-4 mt-5 mb-3">Exercises in this Workout</h2>
        <div v-if="workout.exercises.length > 0">
          <div v-for="(exercise, index) in workout.exercises" :key="index" class="card mb-3">
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
        <p v-else>No exercises in this workout yet. Add one below!</p>

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
          <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</template>
