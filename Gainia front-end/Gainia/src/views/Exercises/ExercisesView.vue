<script setup>
import { ref } from 'vue';
import ExercisesMain from '../../components/Exercises/ExercisesMain.vue'
import ExercisesCustom from '../../components/Exercises/ExercisesCustom.vue'


</script>

<template>
  <main class="page">
    <a href="/exercises/add" class="btn btn-primary me-2">Add exercise</a>
    <ExercisesMain />
    <ExercisesCustom />
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
