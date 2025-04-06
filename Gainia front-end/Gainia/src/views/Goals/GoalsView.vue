<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const goals = ref([]);
const errorMessage = ref("");
const loading = ref(false);

const user = JSON.parse(localStorage.getItem("user"));
const userId = user ? user.user_id : 1; // Change this later to null

async function fetchGoals() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get("http://localhost/goals/user/" + userId);
    goals.value = response.data;
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while fetching goals.";
  } finally {
    loading.value = false;
  }
}

async function deleteGoal(goalId) {
  try {
    await axios.delete(`http://localhost/goals/${goalId}`);
    fetchGoals();
  } catch (error) {
    console.error("Error deleting goal:", error);
    alert("Failed to delete the goal. Please try again.");
  }
}

onMounted(() => {
  fetchGoals();
});
</script>

<template>
  <main class="page container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Goals</h1>

    <a href="/goals/add" class="btn btn-primary btn-lg mb-4">Add Goal</a>

    <div v-if="loading" class="text-center">
      <p>Loading goals...</p>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && goals.length === 0" class="text-center">
      <p>No goals found.</p>
    </div>

    <table v-if="!loading && goals.length > 0" class="table table-striped">
      <thead>
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
  </main>
</template>

<style scoped>
</style>
