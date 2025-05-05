<template>
    <div v-if="errors.general" class="error">{{ errors.general[0] }}</div>
    <form @submit.prevent="login">
      <input v-model="form.email" placeholder="Email" />
      <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>
      <input v-model="form.password" type="password" placeholder="Password" />
      <div v-if="errors.password" class="error">{{ errors.password[0] }}</div>
      <button type="submit">Login</button>
    </form>
  </template>
  
  <script setup>
  import { reactive } from 'vue';
  import { useUserStore } from '@/stores/user';
  import router from '@/router';

  const userStore = useUserStore();
  
  const form = reactive({
    email: '',
    password: ''
  });

  const errors = reactive({})
  
  async function login() {
    Object.keys(errors).forEach(key => delete errors[key])
    const result = await userStore.login(form)
    if (!result.success) {
      Object.assign(errors, result.errors)
    } else {
        router.push('/');
    }

  }
  </script>
  