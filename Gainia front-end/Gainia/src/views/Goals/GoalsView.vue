<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useAuthStore } from "@/stores/auth";

const goals = ref([]);
const errorMessage = ref("");
const loading = ref(true);

const authStore = useAuthStore();
const userId = authStore.user?.user_id;

async function fetchGoals() {
  if (!userId) {
    errorMessage.value = "User not found. Please log in.";
    loading.value = false;
    return;
  }

  loading.value = true;
  errorMessage.value = "";
  try {
    const response = await api.get(`/goals/user/${userId}`);
    goals.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "An error occurred while fetching goals.";
  } finally {
    loading.value = false;
  }
}

async function deleteGoal(goalId) {
  try {
    await api.delete(`/goals/${goalId}`);
    fetchGoals();
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "Failed to delete the goal. Please try again.";
  }
}

onMounted(fetchGoals);
</script>

<template>
  <main class="page container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Goals</h1>

    <div class="text-center mb-4">
      <router-link to="/goals/add" class="btn btn-primary btn-lg">Add Goal</router-link>
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
      <div v-if="goals.length === 0" class="text-center">
        <p class="lead">You haven't set any goals yet.</p>
      </div>
      <table v-else class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Exercise Name</th>
            <th>Target Weight</th>
            <th>Target Reps</th>
            <th>Created At</th>
            <th>Deadline</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="goal in goals" :key="goal.goal_id">
            <td>{{ goal.exercise_name }}</td>
            <td>{{ goal.target_weight }} kg</td>
            <td>{{ goal.target_reps }}</td>
            <td>{{ new Date(goal.created_at).toLocaleDateString() }}</td>
            <td>{{ new Date(goal.deadline).toLocaleDateString() }}</td>
            <td>
              <button class="btn btn-danger btn-sm" @click="deleteGoal(goal.goal_id)">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<style scoped>
</style>
