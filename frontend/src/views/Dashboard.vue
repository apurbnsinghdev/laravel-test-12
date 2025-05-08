<template>
    <div>
      <h1>Welcome to Dashboard</h1>
  
      <div v-if="auth.user">
        <p><strong>Name:</strong> {{ auth.user.name }}</p>
        <p><strong>Email:</strong> {{ auth.user.email }}</p>
      </div>
  
      <div v-else>
        <p>Loading info...</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  import { useUserStore } from '@/stores/user';
  
  const auth = useUserStore();
  
  onMounted(() => {
    if (!auth.user && auth.token) {
      auth.fetchUser();
    }
  });
  
  function logout() {
    auth.logout();
  }
  </script>
  