import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import RoomList from './components/RoomList.vue';
import BookingList from './components/BookingList.vue';
import Login from './components/Login.vue';

const routes = [
  { path: '/', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/rooms', component: RoomList, meta: { requiresAuth: true } },
  { path: '/bookings', component: BookingList, meta: { requiresAuth: true } },
  { path: '/login', component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = window.Laravel && window.Laravel.isAuthenticated;
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else if (to.path === '/login' && isAuthenticated) {
    next('/');
  } else {
    next();
  }
});

export default router;
