<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const prs = ref([]);
const errorMessage = ref("");
const loading = ref(false);

const user = JSON.parse(localStorage.getItem("user"));
const userId = user ? user.user_id : 1; // Change this later to null

async function fetchPRs() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get("http://localhost/pr/user/" + userId);
    prs.value = response.data;
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while fetching personal records.";
  } finally {
    loading.value = false;
  }
}

async function deletePR(prId) {
  try {
    await axios.delete(`http://localhost/pr/${prId}`);
    fetchPRs();
    alert("Personal record deleted successfully!");
  } catch (error) {
    console.error("Error deleting personal record:", error);
    alert("Failed to delete the personal record. Please try again.");
  }
}

onMounted(() => {
  fetchPRs();
});
</script>

<template>
  <main class="page container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Personal Records</h1>

    <div v-if="loading" class="text-center">
      <p>Loading personal records...</p>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && prs.length === 0" class="text-center">
      <p>No personal records found.</p>
    </div>

    <table v-if="!loading && prs.length > 0" class="table table-striped">
      <thead>
        <tr>
          <th>Exercise Name</th>
          <th>Max Weight</th>
          <th>Max Reps</th>
          <th>Record Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pr in prs" :key="pr.pr_id">
          <td>{{ pr.exercise_name }}</td>
          <td>{{ pr.max_weight }} kg</td>
          <td>{{ pr.max_reps }}</td>
          <td>{{ new Date(pr.record_date).toLocaleDateString() }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="deletePR(pr.pr_id)">
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
