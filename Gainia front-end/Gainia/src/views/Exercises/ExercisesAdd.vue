<script setup>
import { ref } from "vue";
import axios from "axios";

const exerciseName = ref("");
const muscleGroup = ref("");
const description = ref("");
const errorMessage = ref("");
const successMessage = ref("");

async function addExercise() {
  try {
    const response = await axios.post("http://localhost/exercises/standard", {
      name: exerciseName.value,
      muscle_group: muscleGroup.value,
      description: description.value,
    });
    successMessage.value = "Exercise added successfully!";
    errorMessage.value = "";
    exerciseName.value = "";
    muscleGroup.value = "";
    description.value = "";
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while adding the exercise.";
    successMessage.value = "";
  }
}
</script>

<template>
  <main>
    <div class="page container py-5">
      <h1 class="display-4 fw-bold text-center mb-4">Add Exercise</h1>
      <form @submit.prevent="addExercise">
        <div class="mb-3">
          <label for="exerciseName" class="form-label">Exercise Name</label>
          <input
            type="text"
            class="form-control"
            id="exerciseName"
            v-model="exerciseName"
            placeholder="Enter exercise name"
            required
          />
        </div>
        <div class="mb-3">
          <label for="exerciseMuscleGroup" class="form-label">Muscle Group</label>
          <select
            class="form-select"
            id="exerciseMuscleGroup"
            v-model="muscleGroup"
            required
          >
            <option value="" disabled selected>Select muscle group</option>
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
          <label for="exerciseDescription" class="form-label">Description</label>
          <textarea
            class="form-control"
            id="exerciseDescription"
            v-model="description"
            rows="3"
            placeholder="Enter exercise description"
          ></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Exercise</button>
        <div v-if="successMessage" class="alert alert-success mt-3">
          {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">
          {{ errorMessage }}
        </div>
      </form>
    </div>
  </main>
</template>

<style scoped>
</style>