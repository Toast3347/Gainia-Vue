<script setup>
import { ref, computed, onMounted } from "vue";
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale } from "chart.js";
import { Line } from "vue-chartjs";
import api from "@/services/api";
import { useAuthStore } from "@/stores/auth";

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale);

const progress = ref([]);
const errorMessage = ref("");
const loading = ref(true);
const selectedExercise = ref("");
const exercises = ref([]);

const authStore = useAuthStore();
const userId = authStore.user?.user_id;

async function fetchProgress() {
  if (!userId) {
    errorMessage.value = "User not found. Please log in.";
    loading.value = false;
    return;
  }

  loading.value = true;
  errorMessage.value = "";
  try {
    const response = await api.get(`/progress/user/${userId}`);
    progress.value = response.data;
    
    const uniqueExercises = [...new Set(progress.value.map(item => item.exercise_name))];
    exercises.value = uniqueExercises;
    
    if (uniqueExercises.length > 0 && !selectedExercise.value) {
      selectedExercise.value = uniqueExercises[0];
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "An error occurred while fetching progress data.";
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
    return { labels: [], datasets: [] };
  }

  const sortedData = [...filtered].sort((a, b) => new Date(a.record_date) - new Date(b.record_date));
  const labels = sortedData.map(item => new Date(item.record_date).toLocaleDateString());

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
    legend: { position: "top" },
    title: {
      display: true,
      text: `Progress Chart: ${selectedExercise.value || 'No exercise selected'}`,
    },
  },
}));

onMounted(fetchProgress);
</script>

<template>
  <main class="page container py-5 min-vh-100">
    <h1 class="display-4 fw-bold text-center mb-4">Progress</h1>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="errorMessage" class="alert alert-danger text-center">
      {{ errorMessage }}
    </div>

    <div v-else-if="progress.length === 0" class="text-center">
        <p class="lead">No progress data available yet. Start logging workouts to see your progress!</p>
    </div>
    
    <div v-else>
      <div class="mb-4 w-50 mx-auto">
        <label for="exerciseSelect" class="form-label">Select Exercise:</label>
        <select id="exerciseSelect" class="form-select" v-model="selectedExercise">
          <option v-for="exercise in exercises" :key="exercise" :value="exercise">
            {{ exercise }}
          </option>
        </select>
      </div>

      <div v-if="filteredData.length > 0" class="chart-container" style="height: 500px">
        <Line :data="chartData" :options="chartOptions" />
      </div>
      
      <div v-else class="alert alert-info text-center">
        No progress data available for the selected exercise.
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>