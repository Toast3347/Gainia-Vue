<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useAuthStore } from "@/stores/auth";

const prs = ref([]);
const errorMessage = ref("");
const loading = ref(true);

const authStore = useAuthStore();
const userId = authStore.user?.user_id;

async function fetchPRs() {
  if (!userId) {
    errorMessage.value = "User not found. Please log in.";
    loading.value = false;
    return;
  }

  loading.value = true;
  errorMessage.value = "";
  try {
    const response = await api.get(`/pr/user/${userId}`);
    prs.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "An error occurred while fetching personal records.";
  } finally {
    loading.value = false;
  }
}

async function deletePR(prId) {
  try {
    await api.delete(`/pr/${prId}`);
    fetchPRs(); 
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "Failed to delete personal record.";
  }
}

onMounted(fetchPRs);
</script>

<template>
  <main class="page container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Personal Records</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>

    <div v-if="!loading && !errorMessage">
      <div v-if="prs.length === 0" class="text-center">
        <p class="lead">You don't have any personal records yet.</p>
      </div>

      <table v-else class="table table-hover align-middle">
        <thead class="table-light">
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
    </div>
  </main>
</template>

<style scoped>
</style>
