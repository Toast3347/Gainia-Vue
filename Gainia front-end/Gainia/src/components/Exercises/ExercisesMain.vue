<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import api from "@/services/api";
import { useRouter } from 'vue-router';
import { useAuthStore } from "@/stores/auth";
const router = useRouter();

const exercises = ref([]);
const errorMessage = ref("");
const loading = ref(false);

const authStore = useAuthStore();
const userRole = authStore.user?.role;


async function fetchExercises() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get("/exercises/standard");
    exercises.value = response.data;
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while fetching exercises.";
  } finally {
    loading.value = false;
  }
}

function editExercise(exerciseId) {
  if (!exerciseId) return;
  router.push(`/exercises/edit/${exerciseId}`);
}

async function deleteExercise(exerciseId) {
  if (confirm("Are you sure you want to delete this exercise?")) {
    try {
      await api.delete(`/exercises/standard/${exerciseId}`);

      fetchExercises();
    } catch (error) {
      console.error("Error deleting exercise:", error);
      alert("Failed to delete the exercise. Please try again.");
    }
  }
}

onMounted(() => {
  fetchExercises();
});
</script>

<template>
  <main>
    <div class="container py-5">
      <h1 class="display-4 fw-bold text-center mb-4">Exercises {{role}}</h1>

      <div v-if="loading" class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div v-if="errorMessage" class="alert alert-danger text-center">
        {{ errorMessage }}
      </div>

      <div v-if="!loading && !errorMessage">
        <div v-if="exercises.length === 0" class="text-center">
          <p class="lead">No exercises found. Add some exercises to get started!</p>
        </div>
        <div v-else>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Muscle Group</th>
                <th>Description</th>
                <th v-if="userRole === 'admin'">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(exercise) in exercises" :key="exercise.exercise_id">
                <td>{{ exercise.name }}</td>
                <td>{{ exercise.muscle_group }}</td>
                <td>{{ exercise.description }}</td>
                <td>
                  <button v-if="userRole === 'admin'"
                    class="btn btn-primary btn-sm me-2"
                    @click="editExercise(exercise.exercise_id)"
                  >
                    Edit
                  </button>
                  <button v-if="userRole === 'admin'"
                    class="btn btn-danger btn-sm"
                    @click="deleteExercise(exercise.exercise_id)"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.table {
  margin-top: 20px;
}

th {
  text-align: left;
}

button {
  font-size: 0.85rem;
}
</style>
