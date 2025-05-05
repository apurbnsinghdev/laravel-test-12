<template>
    <div>
      <h1>Welcome to Dashboard</h1>
  
      <div v-if="auth.user">
        <p><strong>Name:</strong> {{ auth.user.name }}</p>
        <p><strong>Email:</strong> {{ auth.user.email }}</p>
        <button @click="logout">Logout</button>
      </div>
  
      <div v-else>
        <p>Loading info...</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  import { useAuthStore } from '@/stores/auth';
  
  const auth = useAuthStore();
  
  onMounted(() => {
    if (!auth.user && auth.token) {
      auth.fetchUser();
    }
  });
  
  function logout() {
    auth.logout();
  }
  </script>
  