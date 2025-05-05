<template>
    <div>
      <h2>Employees</h2>
      <button @click="showCreateForm = true">+ Add Employee</button>
  
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Department</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="emp in employeeStore.employees" :key="emp.id">
            <td>{{ emp.name }}</td>
            <td>{{ emp.email }}</td>
            <td>{{ emp.department?.name }}</td>
            <td>
              <button @click="editEmployee(emp)">Edit</button>
              <button @click="employeeStore.deleteEmployee(emp.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <EmployeeForm v-if="showCreateForm || selectedEmployee" 
                    :employee="selectedEmployee" 
                    @saved="handleSaved" 
                    @cancel="resetForm" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useEmployeeStore } from '@/stores/employee';
  import EmployeeForm from '@/components/EmployeeForm.vue';
  
  const employeeStore = useEmployeeStore();
  const showCreateForm = ref(false);
  const selectedEmployee = ref(null);
  
  onMounted(() => {
    employeeStore.fetchEmployees();
  });
  
  function editEmployee(emp) {
    selectedEmployee.value = { ...emp };
    showCreateForm.value = true;
  }
  
  function handleSaved() {
    resetForm();
    employeeStore.fetchEmployees();
  }
  
  function resetForm() {
    showCreateForm.value = false;
    selectedEmployee.value = null;
  }
  </script>
  