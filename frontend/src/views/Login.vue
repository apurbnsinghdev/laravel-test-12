<template>
    <form @submit.prevent="login">
      <input v-model="form.email" placeholder="Email" />
      <input v-model="form.password" type="password" placeholder="Password" />
      <button type="submit">Login</button>
    </form>
  </template>
  
  <script setup>
  import { reactive } from 'vue';
  import { useUserStore } from '@/stores/user';
  
  const userStore = useUserStore();
  
  const form = reactive({
    email: '',
    password: ''
  });
  
  function login() {
    userStore.login(form);
  }
  </script>
  