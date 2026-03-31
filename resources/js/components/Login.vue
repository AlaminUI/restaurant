<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Restaurant Booking</h1>
      <p class="text-gray-600 mb-6 text-center">Login to access the dashboard</p>
      
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>

      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            required 
            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            required 
            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>
        <button 
          type="submit" 
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
          :disabled="loading"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({ email: '', password: '' });
const error = ref('');
const loading = ref(false);

const login = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    const response = await window.apiClient.post('/login', form.value);
    localStorage.setItem('token', response.data.token);
    router.push('/');
  } catch (e) {
    error.value = e.response?.data?.error || 'Login failed';
  } finally {
    loading.value = false;
  }
};
</script>
