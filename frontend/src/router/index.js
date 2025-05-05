import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import Dashboard from '@/views/Dashboard.vue';
import Employee from '@/views/Employee.vue';
import { useAuthStore } from '../stores/auth'


const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/employee',
    name: 'Employee',
    component: Employee,
  },
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()
  // List of public pages
  const publicPages = ['/login', '/register', '/employee']
  const authRequired = !publicPages.includes(to.path)

  if (authRequired && !auth.token) {
    next('/login')
  } else {
    next()
  }
})

export default router;
