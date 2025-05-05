<template>
    <div>
      <h2>Employees</h2>
      <button @click="showCreateForm = true">+ Add Employee</button>
      
      <input v-model="search" @input="searchEmployees(1)" placeholder="Search name, email, salary, designation..." />
      
      <table v-show="!showCreateForm && selectedEmployee === null"
      >
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Salary</th>
            <th>Department</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="emp in employeeStore.employees" :key="emp.id">
            <td>{{ emp.name }}</td>
            <td>{{ emp.email }}</td>
            <td>{{ emp.employee_detail.salary }}</td>
            <td>{{ emp.department?.name }}</td>
            <td>
              <button @click="editEmployee(emp)">Edit</button>
              <button @click="employeeStore.deleteEmployee(emp.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="pagination.total > pagination.per_page">
        <button :disabled="pagination.current_page === 1" @click="changePage(pagination.current_page - 1)">
          Prev
        </button>

        Page {{ pagination.current_page }} of {{ pagination.last_page }}

        <button :disabled="pagination.current_page === pagination.last_page" @click="changePage(pagination.current_page + 1)">
          Next
        </button>
      </div>
  
      <EmployeeForm 
  v-if="showCreateForm || selectedEmployee" 
  :employee="selectedEmployee" 
  @saved="handleSaved" 
  @cancel="resetForm" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useEmployeeStore } from '@/stores/employee';
  import EmployeeForm from '@/components/EmployeeForm.vue';
  import api from '@/utils/api';
  
  const employeeStore = useEmployeeStore();
  const showCreateForm = ref(false);
  const selectedEmployee = ref(null);

  const employees = ref([]);
  const search = ref('');
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0,
  });

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
  async function deleteEmployee(id) {
    if (confirm('Are you sure to delete?')) {
      const result = await employeeStore.deleteEmployee(id);
    }
  }
  async function searchEmployees(page = 1) {

    const res = await api.get('api/employees', {
      params: {
        search: search.value,
        page: page
      }
    });

    employees.value = res.data.data || res.data;
    pagination.value = {
      current_page: res.data.current_page,
      last_page: res.data.last_page,
      per_page: res.data.per_page,
      total: res.data.total,
    };
    employees.value = res.data.data || res.data;  
  }
  async function changePage(page) {
    searchEmployees(page);
  }
  </script>
  