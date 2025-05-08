<template>
  <div>
    <h3>{{ employee?.id ? 'Edit' : 'Create' }} Employee</h3>
    <div v-if="errors.general" class="error">{{ errors.general[0] }}</div>
    <form @submit.prevent="saveEmployee">
      <input v-model="employee.name" placeholder="Name" />
      <div v-if="errors.name" class="error">{{ errors.name[0] }}</div>
      <input v-model="employee.email" placeholder="Email" />
      <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>
      <select v-model="employee.department_id">
        <option disabled value="">Select Department</option>
        <option v-for="dept in departments" :value="dept.id" :key="dept.id">{{ dept.name }}</option>
      </select>
      <div v-if="errors.department_id" class="error">{{ errors.department_id[0] }}</div>
      <input v-model="employee.employee_detail.designation" placeholder="Designation" />
      <div v-if="errors.designation" class="error">{{ errors.designation[0] }}</div>
      <input v-model="employee.employee_detail.salary" type="number" min="0" step="0.01" placeholder="Salary" />
      <div v-if="errors.salary" class="error">{{ errors.salary[0] }}</div>
      <input v-model="employee.employee_detail.address" placeholder="Address" />
      <div v-if="errors.address" class="error">{{ errors.address[0] }}</div>
      <input v-model="employee.employee_detail.joined_date" type="date" placeholder="Join Date" />
      <div v-if="errors.joined_date" class="error">{{ errors.joined_date[0] }}</div>
      <button type="submit">Save</button>
      <button type="button" @click="$emit('cancel')">Cancel</button>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, reactive } from 'vue';
import { useEmployeeStore } from '@/stores/employee';
import api from '@/utils/api';

const props = defineProps({
  employee: {
    type: Object,
    default: () => null // Ensures employee is null by default
  }
});

const emit = defineEmits(['saved', 'cancel']);

const employeeStore = useEmployeeStore();

const form = reactive({
  name: '',
  email: '',
  department_id: null,
  employee_detail: {
    salary: '',
    designation: '',
    address: '',
    joined_date: ''
  }
});

const departments = ref([]);
const employee = ref({
  name: '',
  email: '',
  department_id: '',
  employee_detail: {
    salary: '',
    designation: '',
    address: '',
    joined_date: ''
  }
})
const errors = reactive({});

watch(() => props.employee, (newEmp) => {
  if (newEmp) {
    employee.value = {
      ...newEmp,
      employee_detail: newEmp.employee_detail || {
        salary: '',
        designation: '',
        address: '',
        joined_date: ''
      }
    }
  }
}, { immediate: true })

onMounted(async () => {
  const res = await api.get('api/departments');
  departments.value = res.data.data || res.data; // adjust as per your dept API
});

function resetForm() {
  Object.assign(form, {
    name: '',
    email: '',
    department_id: null,
    designation: '',
    salary: null,
    address: '',
    joined_date: ''
  });
}

async function saveEmployee() {
  Object.keys(errors).forEach(key => delete errors[key]);

  let result = null;

  try {
    if (props.employee?.id) {
      result = await employeeStore.updateEmployee(props.employee.id, employee.value);
    } else {
      result = await employeeStore.createEmployee(employee.value);
    }

    if (!result || !result.success) {
      console.error('Error: ', result);
      Object.assign(errors, result?.errors || { general: ['Unknown error occurred'] });
    } else {
      emit('saved');
      resetForm();
    }

  } catch (error) {
    console.error('Error during save operation:', error);
    Object.assign(errors, { general: ['Something went wrong. Please try again.'] });
  }
}

</script>

<style>/* Basic form container */
form {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  font-family: Arial, sans-serif;
}

/* Form labels */
label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #333;
}

/* Text inputs, email, password */
input[type="text"],
input[type="email"],
input[type="password"],
textarea,
select {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
  font-size: 14px;
}

/* Focus effect */
input:focus,
textarea:focus,
select:focus {
  border-color: #007BFF;
  outline: none;
}

/* Submit button */
button[type="submit"] {
  background-color: #007BFF;
  color: white;
  border: none;
  padding: 12px;
  width: 100%;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
}

button[type="submit"]:hover {
  background-color: #0056b3;
}


</style>
