<template>
    <header class="topbar">
      <div class="user-info" v-if="auth.user">
        <span>Welcome, {{ auth.user.name }}</span>
        <a href="#" @click.prevent="logout">Logout</a>
      </div>
    </header>
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
        alert('Logged out!')
    }
  </script>
  
  <style scoped>
  .topbar {
    background-color: #ecf0f1;
    padding: 10px 20px;
    display: flex;
    justify-content: flex-end;
  }
  .user-info span {
    margin-right: 10px;
  }
  </style>
  