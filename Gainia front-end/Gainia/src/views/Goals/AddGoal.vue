<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "@/services/api";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const goal = ref({
  exercise_id: null,
  custom_exercise_id: null,
  target_weight: '',
  target_reps: '',
  deadline: ''
});

const selectedExercise = ref(null);
const allAvailableExercises = ref([]);

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

async function handleAddGoal() {
  errorMessage.value = '';
  successMessage.value = '';

  if (!selectedExercise.value) {
    errorMessage.value = "Please select an exercise.";
    return;
  }
  
  try {
    const payload = {
      user_id: userId,
      exercise_id: selectedExercise.value.type === 'standard' ? selectedExercise.value.id : null,
      custom_exercise_id: selectedExercise.value.type === 'custom' ? selectedExercise.value.id : null,
      target_weight: goal.value.target_weight,
      target_reps: goal.value.target_reps,
      deadline: goal.value.deadline,
    };
    
    await api.post(`/goals`, payload);
    successMessage.value = "Goal added successfully! Redirecting...";
    setTimeout(() => router.push('/goals'), 2000);

  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "Failed to add goal.";
  }
}

onMounted(fetchAllExercises);
</script>

<template>
  <main class="page container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Add New Goal</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

    <form @submit.prevent="handleAddGoal" v-if="!loading && !successMessage" class="w-75 mx-auto">
      <div class="mb-3">
        <label for="exercise" class="form-label">Exercise</label>
        <select id="exercise" class="form-select" v-model="selectedExercise" required>
          <option :value="null" disabled>Select an exercise</option>
          <optgroup label="Standard Exercises">
            <option v-for="ex in allAvailableExercises.filter(e => e.type === 'standard')" :key="ex.id" :value="ex">{{ ex.name }}</option>
          </optgroup>
          <optgroup label="Custom Exercises">
            <option v-for="ex in allAvailableExercises.filter(e => e.type === 'custom')" :key="ex.id" :value="ex">{{ ex.name }}</option>
          </optgroup>
        </select>
      </div>

      <div class="mb-3">
        <label for="targetWeight" class="form-label">Target Weight (kg)</label>
        <input type="number" id="targetWeight" class="form-control" v-model.number="goal.target_weight" placeholder="e.g., 100" required />
      </div>

      <div class="mb-3">
        <label for="targetReps" class="form-label">Target Reps</label>
        <input type="number" id="targetReps" class="form-control" v-model.number="goal.target_reps" placeholder="e.g., 5" required />
      </div>

      <div class="mb-3">
        <label for="deadline" class="form-label">Deadline</label>
        <input type="date" id="deadline" class="form-control" v-model="goal.deadline" required />
      </div>

      <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Add Goal</button>
    </form>
  </main>
</template>

<style scoped>
</style>
