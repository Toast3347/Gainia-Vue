<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale } from "chart.js";
import { Line } from "vue-chartjs";
import axios from "axios";

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale);

const progress = ref([]);
const errorMessage = ref("");
const loading = ref(false);
const selectedExercise = ref("");
const exercises = ref([]);

const user = JSON.parse(localStorage.getItem("user"));
const userId = user ? user.user_id : 1; // Change this later to null

async function fetchProgress() {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get("http://localhost/progress/user/" + userId);
    progress.value = response.data;
    
    const uniqueExercises = [...new Set(progress.value.map(item => item.exercise_name))];
    exercises.value = uniqueExercises;
    
    if (uniqueExercises.length > 0 && !selectedExercise.value) {
      selectedExercise.value = uniqueExercises[0];
    }
  } catch (error) {
    errorMessage.value = error.response
      ? error.response.data.message
      : "An error occurred while fetching progress data.";
  } finally {
    loading.value = false;
  }
}

const filteredData = computed(() => {
  if (!selectedExercise.value) return [];
  return progress.value.filter(item => item.exercise_name === selectedExercise.value);
});

const chartData = computed(() => {
  const filtered = filteredData.value;
  if (filtered.length === 0) {
    return {
      labels: [],
      datasets: []
    };
  }

  const sortedData = [...filtered].sort((a, b) => new Date(a.record_date) - new Date(b.record_date));
  
  const labels = sortedData.map(item => {
    const date = new Date(item.record_date);
    return date.toLocaleDateString();
  });

  return {
    labels,
    datasets: [
      {
        label: "Weight (kg)",
        data: sortedData.map(item => parseFloat(item.weight)),
        borderColor: "rgba(75, 192, 192, 1)",
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        tension: 0.4,
      },
      {
        label: "Reps",
        data: sortedData.map(item => item.reps),
        borderColor: "rgba(153, 102, 255, 1)",
        backgroundColor: "rgba(153, 102, 255, 0.2)",
        tension: 0.4,
      },
    ],
  };
});

const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: "top",
    },
    title: {
      display: true,
      text: `Progress Chart: ${selectedExercise.value || 'No exercise selected'}`,
    },
  },
}));

onMounted(() => {
  fetchProgress();
});
</script>

<template>
  <main class="page container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Progress</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div v-else>
      <div class="mb-4">
        <label for="exerciseSelect" class="form-label">Select Exercise:</label>
        <select 
          id="exerciseSelect" 
          class="form-select" 
          v-model="selectedExercise"
        >
          <option v-for="exercise in exercises" :key="exercise" :value="exercise">
            {{ exercise }}
          </option>
        </select>
      </div>

      <div v-if="filteredData.length > 0" class="chart-container">
        <Line :data="chartData" :options="chartOptions" />
      </div>
      
      <div v-else class="alert alert-info">
        No progress data available for the selected exercise.
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>