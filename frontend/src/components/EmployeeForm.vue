<template>
    <div>
      <h3>{{ employee?.id ? 'Edit' : 'Create' }} Employee</h3>
      <form @submit.prevent="saveEmployee">
        <input v-model="form.name" placeholder="Name" />
        <input v-model="form.email" placeholder="Email" />
        <select v-model="form.department_id">
          <option disabled value="">Select Department</option>
          <option v-for="dept in departments" :value="dept.id" :key="dept.id">{{ dept.name }}</option>
        </select>
  
        <button type="submit">Save</button>
        <button type="button" @click="$emit('cancel')">Cancel</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  import { useEmployeeStore } from '@/stores/employee';
  import axios from 'axios';
  
  const props = defineProps({
    employee: Object
  });
  
  const emit = defineEmits(['saved', 'cancel']);
  
  const employeeStore = useEmployeeStore();
  
  const form = ref({
    name: '',
    email: '',
    department_id: ''
  });
  
  const departments = ref([]);
  
  watch(() => props.employee, (newVal) => {
    if (newVal) {
      form.value = { 
        name: newVal.name, 
        email: newVal.email, 
        department_id: newVal.department_id 
      };
    } else {
      resetForm();
    }
  });
  
  onMounted(async () => {
    const res = await axios.get('/api/departments');
    departments.value = res.data.data || res.data; // adjust as per your dept API
  });
  
  function resetForm() {
    form.value = {
      name: '',
      email: '',
      department_id: ''
    };
  }
  
  async function saveEmployee() {
    if (props.employee?.id) {
      await employeeStore.updateEmployee(props.employee.id, form.value);
    } else {
      await employeeStore.createEmployee(form.value);
    }
    emit('saved');
    resetForm();
  }
  </script>
  