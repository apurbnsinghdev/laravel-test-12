<template>
    <header class="topbar">
      <div class="user-info" v-if="auth.user">
        <span>Welcome, {{ user.name }}</span>
        <a href="#" @click.prevent="logout">Logout</a>
      </div>
    </header>
  </template>
  
  <script setup>
import { onMounted } from 'vue';
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';

const auth = useUserStore();
const { user, token } = storeToRefs(auth);  // This makes them reactive in the template

onMounted(() => {
  if (!user.value && token.value) {
    auth.fetchUser();
  }
});

function logout() {
  auth.logout();
  alert('Logged out!');
}
</script>