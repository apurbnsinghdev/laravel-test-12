<template>
  <aside class="sidebar">
    <div class="logo">My Test SaaS</div>
    <nav class="nav">
      <ul v-if="user">
          <li><router-link to="/">Dashboard</router-link></li>
          <li><router-link to="/employee">Employee</router-link></li>
          <li><button @click="logout">Logout</button></li>
      </ul>
      <ul v-else>
          <li><router-link to="/login">Login</router-link></li>
          <li><router-link to="/register">Register</router-link></li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';

const auth = useUserStore();
const { user } = storeToRefs(auth); 

function logout() {
  auth.logout();
}
</script>

<style scoped>
.sidebar {
  width: 200px;
  background-color: #2c3e50;
  color: white;
  height: 100vh;
  padding: 20px;
}

.logo {
  font-size: 20px;
  margin-bottom: 30px;
}

.nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav ul li {
  margin: 10px 0;
  display: block;
}

.nav ul li a {
  display: block;
  width: 100%;
  padding: 10px 15px;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.2s ease;
}
</style>
