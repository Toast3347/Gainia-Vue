<script>
import axios from 'axios';

function handleEvent(event) {
  event.preventDefault();
  const form = event.target;
  const username = form.username.value;
  const password = form.password.value;
  if (username && password) {
    login();
  } else {
    alert('Please fill in both fields.');
  }
  form.reset();
  return false;
}

async function login(){
  try {
    const response = await axios.post('http://localhost/login', {
      username: this.username,
      password: this.password
    });
    if (response.data.success) {
      window.location.href = "/dashboard";
    } else {
      alert('Login failed: ' + response.data.message);
    }
  } catch (error) {
    console.error('Login error:', error);
    alert('An error occurred while logging in.');
  }

}
</script>
<template>
  <div class="container mt-5 min-vh-100">
    <h1 class="text-center mb-4">Login</h1>
  <form method="POST" @submit="handleEvent" class="w-50 mx-auto">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="/createaccount" class="btn btn-secondary">Create Account</a>
        </div>
      </form>
  </div>
</template>

