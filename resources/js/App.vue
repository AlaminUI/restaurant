<template>
  <div class="min-h-screen bg-gray-100">
    <header v-if="showNav" class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Restaurant & Room Booking</h1>
        <button @click="logout" class="text-red-600 hover:text-red-800 text-sm">Logout</button>
      </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
      <div v-if="showNav" class="mb-6 flex space-x-4">
        <router-link to="/" class="px-4 py-2 rounded" :class="$route.path === '/' ? 'bg-blue-600 text-white' : 'bg-gray-200'">Dashboard</router-link>
        <router-link to="/rooms" class="px-4 py-2 rounded" :class="$route.path === '/rooms' ? 'bg-blue-600 text-white' : 'bg-gray-200'">Rooms</router-link>
        <router-link to="/bookings" class="px-4 py-2 rounded" :class="$route.path === '/bookings' ? 'bg-blue-600 text-white' : 'bg-gray-200'">Bookings</router-link>
      </div>
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const showNav = computed(() => {
  return route.path !== '/login' && localStorage.getItem('token');
});

const logout = () => {
  localStorage.removeItem('token');
  router.push('/login');
};
</script>
