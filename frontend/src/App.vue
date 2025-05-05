<template>
  <div id="app" class="app-layout">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <div class="main-content">
      <!-- Topbar -->
      <Topbar />

      <!-- Page Content -->
      <main class="page-content">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from './components/Sidebar.vue'
import Topbar from './components/Topbar.vue'
</script>

<style>
.app-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.page-content {
  padding: 20px;
}
</style>