<script setup>
import { RouterView } from 'vue-router'
import TheHeader from './components/Header.vue'
import TheFooter from './components/Footer.vue'
</script>

<template>
  <TheHeader />
  <RouterView />
  <TheFooter />
</template>

<style scoped>
</style>
