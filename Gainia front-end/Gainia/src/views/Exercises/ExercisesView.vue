<script setup>
import ExercisesMain from '../../components/Exercises/ExercisesMain.vue'
import ExercisesCustom from '../../components/Exercises/ExercisesCustom.vue'
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const userRole = computed(() => authStore.user?.role);

</script>

<template>
  <main class="page">
    <div class="container mt-4">
    <a href="/exercises/add" class="btn btn-primary me-2">Add exercise</a>
    </div>

    <ExercisesMain />
    <!--Only for user, because admin doesn't need custom exercises-->
    <ExercisesCustom v-if="userRole === 'user'" />
  </main>
</template>

<style scoped>
.buttons {
  margin-bottom: 20px;
}
button {
  margin-right: 10px;
  padding: 8px 16px;
}
button.active {
  background-color: #42b983;
  color: white;
}
</style>
