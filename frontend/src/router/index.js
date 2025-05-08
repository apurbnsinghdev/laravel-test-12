import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/stores/user';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import Dashboard from '@/views/Dashboard.vue';
import Employee from '@/views/Employee.vue';

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
    meta: { requiresAuth: true },
  },
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useUserStore()
  // List of public pages
  //const publicPages = ['/login', '/register', '/employee']
  //const authRequired = !publicPages.includes(to.path)
  if (to.meta.requiresAuth && !auth.user) {
    next('/login')
  } else {
    next()
  }
})

export default router;
