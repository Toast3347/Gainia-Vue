<script setup>
import { useAuthStore } from '@/stores/auth';
import { computed } from 'vue'
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isLoggedIn = computed(() => authStore.isAuthenticated);
const userRole = computed(() => authStore.user?.role);

const logout = () => {
  authStore.logout();
};

const login = () => {
  router.push('/login');
};

const dashboardUrl = computed(() => {
  return userRole.value === 'admin' ? '/admin' : '/dashboard';
});

</script>

<template>
  <header class="sticky-top">
    <nav class="navbar navbar-expand-md navbar-light">
      <a href="/" class="navbar-brand d-flex align-items-center">
        <img
          src="@/assets/images/Gainia_logo_transparentv2.png"
          alt="Gainia Logo"
          class="rounded img-fluid"
        />
        <span class="ms-2 fw-bold fs-4">Gainia</span>
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <template v-if="isLoggedIn">
            <li class="nav-item">
              <a class="nav-link" :href="dashboardUrl">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/exercises">Exercises</a>
            </li>
          </template>
          <template v-if="isLoggedIn && userRole !== 'admin'">
            <li class="nav-item">
              <a class="nav-link" href="/goals">Goals</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/progress">Progress</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/personalrecords">PR's</a>
            </li>
          </template>
        </ul>
        <div class="d-flex ms-3">
          <button v-if="!isLoggedIn" @click="login" class="btn btn-primary me-2">Login</button>
          <button v-else @click="logout" class="btn btn-primary me-2">Logout</button>
        </div>
      </div>
    </nav>
  </header>
</template>

<style scoped>

</style>
