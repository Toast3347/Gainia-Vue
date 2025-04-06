<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const exercises = ref([]);
const errorMessage = ref("");
const loading = ref(false);

async function fetchExercises() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get("http://localhost/exercises/standard");
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
  alert(`Edit exercise with ID: ${exerciseId}`);
  // Add your edit logic here
}

async function deleteExercise(exerciseId) {
  if (confirm("Are you sure you want to delete this exercise?")) {
    try {
      await axios.delete(`http://localhost/exercises/standard/${exerciseId}`);

      fetchExercises();

      alert("Exercise deleted successfully!");
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
      <h1 class="display-4 fw-bold text-center mb-4">Exercises</h1>

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
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(exercise, index) in exercises" :key="exercise.exercise_id">
                <td>{{ exercise.name }}</td>
                <td>{{ exercise.muscle_group }}</td>
                <td>{{ exercise.description }}</td>
                <td>
                  <button
                    class="btn btn-primary btn-sm me-2"
                    @click="editExercise(exercise.exercise_id)"
                  >
                    Edit
                  </button>
                  <button
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
