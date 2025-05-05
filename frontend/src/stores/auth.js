import { defineStore } from 'pinia';
import axios from 'axios';
import api from '@/utils/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  actions: {
    async login(credentials) {
      const res = await axios.post('api/login', credentials);
      this.token = res.data.token;
      localStorage.setItem('token', this.token);
      await this.fetchUser();
    },

    async fetchUser() {
      const res = await axios.get('api/user', {
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
