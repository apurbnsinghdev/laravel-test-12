import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import './style.css'
import App from './App.vue'
import api from '@/utils/api';

const app = createApp(App)
app.use(createPinia())
app.use(router)

api.get('/sanctum/csrf-cookie')
  .then(() => {
    console.log('CSRF cookie set!');
    app.mount('#app');
  });