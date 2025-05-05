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
        const res = await api.get('api/employees');
        this.employees = res.data.data;
    },
    async fetchEmployee(id) {
      const res = await api.get(`api/employees/${id}`);
      this.employee = res.data;
    },
    async createEmployee(data) {
      try {
        await api.post('api/employees', data);
        const result =  { success: true, message: 'Employee created successful!' }
        await this.fetchEmployees();
        return Promise.resolve(result);
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    },
    async updateEmployee(id, data) {
      try{
        await api.put(`api/employees/${id}`, data);
        const result =  { success: true, message: 'Employee updated successful!' }
        await this.fetchEmployees();
        return Promise.resolve(result);
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    },
    async deleteEmployee(id) {
      try{      
        await api.delete(`api/employees/${id}`);
        await this.fetchEmployees();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    }
  }
});
