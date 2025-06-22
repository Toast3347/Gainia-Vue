<script setup>
import { useRouter } from 'vue-router';
</script>

<template>
  <main>
    <div class="page container-fluid px-0">
      <div class="jumbotron p-3 p-md-5 text-white bg-light">
        <div class="col-md-6 px-0">
          <h1 class="display-4 text-dark">Welcome to Gainia</h1>
          <p class="lead my-3 text-dark">Gainia is your ultimate fitness companion, designed to help you track your progress and achieve your goals. Explore our features and start your fitness journey today!</p>
          <router-link to="/create-account" class="btn btn-primary mt-3">Create an account!</router-link>
        </div>
      </div>

      <div class="container marketing mt-5">
        <div class="row text-center">
          <div class="col-lg-4">
            <h2 class="fw-normal">Track with Precision</h2>
            <p>Seamlessly log every workout, from exercises and sets to reps and weight. Our intuitive interface makes tracking your fitness journey effortless.</p>
          </div>
          <div class="col-lg-4">
            <h2 class="fw-normal">Set & Crush Goals</h2>
            <p>Define your ambitions with our goal-setting feature. Set clear targets for weight and reps with deadlines to keep you focused and accountable on your path to success.</p>
          </div>
          <div class="col-lg-4">
            <h2 class="fw-normal">Stay Motivated</h2>
            <p>Visualize your hard work with dynamic progress charts. With automatic personal record and progress tracking, you'll see your strength grow and stay motivated every step of the way.</p>
          </div>
        </div>
      </div>

    </div>
  </main>
</template>