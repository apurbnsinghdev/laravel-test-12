import { defineStore } from 'pinia';
import api from '@/utils/api';

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: null,
  }),
  actions: {
    async register(form) {
      try {
        const res = await api.post('api/register', form)
        this.user = res.data.user
        this.token = res.data.token
        return { success: true, message: 'User created successful!' }
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    },

    async fetchUser() {
      if (!this.token) return;

      const { data } = await api.get('api/me', {
        headers: { Authorization: `Bearer ${this.token}` }
      });
      this.user = data;
    },

    async login(credentials) {
      try {
        const res = await api.post('api/login', credentials);
        this.token = res.data.token;
        localStorage.setItem('token', this.token);
        await this.fetchUser();
        return { success: true, message: 'Login successful!' }
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    },
    async logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
    },
  }
})