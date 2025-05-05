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
        await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
        const res = await api.post('/register', form)
        this.user = res.data.user
        this.token = res.data.token
        return { success: true } 
      } catch (error) {
        if (error.response && error.response.status === 422) {
          return { success: false, errors: error.response.data.errors } 
        } else {
          return { success: false, errors: { general: ['Something went wrong.'] } }
        }
      }
    },

    async login(credentials) {
      await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
      const res = await api.post('/login', credentials);
      this.token = res.data.token;
      localStorage.setItem('token', this.token);
      await this.fetchUser();
    },
  }
})