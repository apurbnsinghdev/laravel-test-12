import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  actions: {
    async login(credentials) {
      await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
      const res = await axios.post('/login', credentials);
      this.token = res.data.token;
      localStorage.setItem('token', this.token);
      await this.fetchUser();
    },

    async fetchUser() {
      await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
      const res = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${this.token}` }
      });
      this.user = res.data;
    },

    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
    }
  }
});
