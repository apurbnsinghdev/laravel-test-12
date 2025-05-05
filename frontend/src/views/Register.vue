<template>
  <form @submit.prevent="register">
    <input v-model="form.name" placeholder="Name" />
    <div v-if="errors.name" class="error">{{ errors.name[0] }}</div>

    <input v-model="form.email" placeholder="Email" />
    <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>

    <input v-model="form.password" type="password" placeholder="Password" />
    <div v-if="errors.password" class="error">{{ errors.password[0] }}</div>

    <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" />
    <div v-if="errors.password_confirmation" class="error">{{ errors.password_confirmation[0] }}</div>


    <div v-if="errors.general" class="error">{{ errors.general[0] }}</div>

    <button type="submit">Register</button>
  </form>
</template>

<script setup>
import { reactive } from 'vue';
import { useUserStore } from '@/stores/user';
import router from '@/router';
const userStore = useUserStore();

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation:''
});

const errors = reactive({})

async function register() {
  
  Object.keys(errors).forEach(key => delete errors[key])

  const result = await userStore.register(form)

  if (!result.success) {
    Object.assign(errors, result.errors)
  } else {
      router.push('/');
  }
}
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
</style>
