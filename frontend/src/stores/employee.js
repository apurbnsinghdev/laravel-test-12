import { defineStore } from 'pinia';
import axios from 'axios';
import api from '@/utils/api';


export const useEmployeeStore = defineStore('employee', {
  state: () => ({
    employees: [],
    employee: null,
  }),
  actions: {
    async fetchEmployees() {
        await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
        const res = await api.get('employees');
        this.employees = res.data.data;
    },
    async fetchEmployee(id) {
      const res = await api.get(`employees/${id}`);
      this.employee = res.data;
    },
    async createEmployee(data) {
      await api.post('employees', data);
      await this.fetchEmployees();
    },
    async updateEmployee(id, data) {
      await api.put(`employees/${id}`, data);
      await this.fetchEmployees();
    },
    async deleteEmployee(id) {
      await api.delete(`employees/${id}`);
      await this.fetchEmployees();
    }
  }
});
