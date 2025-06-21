<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const exerciseId = ref(""); // Stores the selected exercise ID
const exerciseKey = ref("");
const exerciseType = ref(""); // Stores the type of the selected exercise (standard or custom)
const targetWeight = ref("");
const targetReps = ref("");
const deadline = ref("");
const errorMessage = ref("");
const successMessage = ref("");

const exercises = ref([]);
const loading = ref(false);

const user = JSON.parse(localStorage.getItem("user"));
const userId = user ? user.user_id : 1; // Change this later to null

async function addGoal() {
  clearMessages();

  if (!checkFields()) return;

  try {
    const payload = {
      user_id: userId,
      target_weight: targetWeight.value,
      target_reps: targetReps.value,
      deadline: new Date(deadline.value),
      achieved: false,
    };

    if (exerciseType.value === "standard") {
      payload.exercise_id = exerciseId.value;
    } else if (exerciseType.value === "custom") {
      payload.custom_exercise_id = exerciseId.value;
    }

    const response =await axios.post("http://localhost/goals", payload);
    if (response.status === 201) {
      successMessage.value = "Exercise added successfully!";
      clearForm();
    }
    else {
      errorMessage.value = "Failed to add goal.";
    }
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while adding the goal.";
  }
}

async function fetchAllExercises() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get(`http://localhost/exercises/all/user/${userId}`);
    exercises.value = response.data;
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while fetching exercises.";
  } finally {
    loading.value = false;
  }
}

//fix

function updateExerciseType() {
  const [id, type] = exerciseKey.value.split("-")
  const selectedExercise = exercises.value.find(
    ex => String(ex.id) === id && ex.type === type
  )
  exerciseType.value = selectedExercise ? selectedExercise.type : ""
}

function clearMessages() {
  errorMessage.value = "";
  successMessage.value = "";
}

function clearForm() {
  exerciseId.value = "";
  exerciseType.value = "";
  targetWeight.value = "";
  targetReps.value = "";
  deadline.value = "";
}

function checkFields() {
  if (!exerciseId.value || !exerciseType.value || !targetWeight.value || !targetReps.value || !deadline.value) {
    errorMessage.value = "All fields are required.";
    return false;
  }
  return true;
}

onMounted(() => {
  fetchAllExercises();
});
</script>

<template>
  <main class="page container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Add Goal</h1>

    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div v-if="successMessage" class="alert alert-success">
      {{ successMessage }}
    </div>

    <form @submit.prevent="addGoal">
      <div class="mb-3">
        <label for="exerciseId" class="form-label">Exercise Name</label>
        <select
          id="exerciseId"
          class="form-select"
          v-model="exerciseId"
          @change="updateExerciseType()"
          required
        >
          <option value="" disabled>Select an exercise</option>
          <option
            v-for="ex in exercises"
            :key="`${ex.id}-${ex.type}`"
            :value="`${ex.id}-${ex.type}`"
          >
            {{ ex.name }} ({{ ex.type }})
        </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="targetWeight" class="form-label">Target Weight (kg)</label>
        <input
          type="number"
          id="targetWeight"
          class="form-control"
          v-model="targetWeight"
          placeholder="Enter target weight"
          required
        />
      </div>

      <div class="mb-3">
        <label for="targetReps" class="form-label">Target Reps</label>
        <input
          type="number"
          id="targetReps"
          class="form-control"
          v-model="targetReps"
          placeholder="Enter target reps"
          required
        />
      </div>

      <div class="mb-3">
        <label for="deadline" class="form-label">Deadline</label>
        <input
          type="date"
          id="deadline"
          class="form-control"
          v-model="deadline"
          required
        />
      </div>

      <button type="submit" class="btn btn-primary">Add Goal</button>
    </form>
  </main>
</template>

<style scoped>
</style>
