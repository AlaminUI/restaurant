<template>
  <div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-3xl font-bold text-gray-900">Restaurant & Room Booking</h1>
      </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
      <div class="mb-6 flex space-x-4">
        <button
          @click="activeTab = 'dashboard'"
          :class="['px-4 py-2 rounded', activeTab === 'dashboard' ? 'bg-blue-600 text-white' : 'bg-gray-200']"
        >
          Dashboard
        </button>
        <button
          @click="activeTab = 'rooms'"
          :class="['px-4 py-2 rounded', activeTab === 'rooms' ? 'bg-blue-600 text-white' : 'bg-gray-200']"
        >
          Rooms
        </button>
        <button
          @click="activeTab = 'bookings'"
          :class="['px-4 py-2 rounded', activeTab === 'bookings' ? 'bg-blue-600 text-white' : 'bg-gray-200']"
        >
          Bookings
        </button>
      </div>

      <Dashboard v-if="activeTab === 'dashboard'" />
      <RoomList v-else-if="activeTab === 'rooms'" />
      <BookingList v-else-if="activeTab === 'bookings'" />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Dashboard from './components/Dashboard.vue';
import RoomList from './components/RoomList.vue';
import BookingList from './components/BookingList.vue';

const activeTab = ref('dashboard');
</script>
