<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
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
