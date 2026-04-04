<template>
  <div>
    <div class="grid gap-4 md:grid-cols-3 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Total Rooms</p>
        <p class="text-3xl font-bold">{{ stats.totalRooms }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Available Rooms</p>
        <p class="text-3xl font-bold text-green-600">{{ stats.availableRooms }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Total Bookings</p>
        <p class="text-3xl font-bold text-blue-600">{{ stats.totalBookings }}</p>
      </div>
    </div>

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">API Keys</h2>
      <button
        @click="showForm = !showForm"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        {{ showForm ? 'Cancel' : 'Generate Key' }}
      </button>
    </div>

    <div v-if="showForm" class="bg-white p-6 rounded-lg shadow mb-6">
      <form @submit.prevent="generateKey" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Key Name *</label>
            <input v-model="form.name" type="text" required placeholder="e.g., My Mobile App" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Expires In (days)</label>
            <input v-model="form.days" type="number" min="1" placeholder="365" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Generate
        </button>
      </form>
    </div>

    <div v-if="newKey" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      <p class="font-bold">New API Key Generated:</p>
      <p class="font-mono bg-white p-2 rounded mt-2 select-all">{{ newKey }}</p>
      <p class="text-sm mt-1">Copy this now - you won't see it again!</p>
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="key in apiKeys" :key="key.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ key.name }}</td>
            <td class="px-6 py-4 font-mono text-sm">{{ key.key }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                @click="toggleStatus(key)"
                :class="['px-2 py-1 rounded text-sm', key.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']"
              >
                {{ key.is_active ? 'Active' : 'Inactive' }}
              </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              {{ key.expires_at ? formatDate(key.expires_at) : 'Never' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button @click="copyKey(key.key)" class="text-blue-600 hover:text-blue-900 mr-3">Copy</button>
              <button @click="deleteKey(key.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="apiKeys.length === 0" class="text-center text-gray-500 py-8">
      No API keys found. Generate your first key!
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const apiKeys = ref([]);
const showForm = ref(false);
const newKey = ref('');
const error = ref('');
const stats = ref({
  totalRooms: 0,
  availableRooms: 0,
  totalBookings: 0
});

const form = ref({
  name: '',
  days: 365
});

const fetchApiKeys = async () => {
  try {
    const response = await window.apiClient.get('/dashboard/api-keys', {
      headers: { 'X-API-Key': window.Laravel.apiKey }
    });
    apiKeys.value = response.data;
  } catch (e) {
    error.value = 'Failed to fetch API keys';
  }
};

const fetchStats = async () => {
  try {
    const response = await window.apiClient.get('/dashboard/stats', {
      headers: { 'X-API-Key': window.Laravel.apiKey }
    });
    stats.value = response.data;
  } catch (e) {
    console.error('Failed to fetch stats');
  }
};

const generateKey = async () => {
  try {
    error.value = '';
    newKey.value = '';
    const response = await window.apiClient.post('/dashboard/api-keys', form.value, {
      headers: { 'X-API-Key': window.Laravel.apiKey }
    });
    newKey.value = response.data.key;
    form.value = { name: '', days: 365 };
    showForm.value = false;
    fetchApiKeys();
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to generate key';
  }
};

const toggleStatus = async (key) => {
  try {
    await window.apiClient.patch(`/dashboard/api-keys/${key.id}`, { is_active: !key.is_active }, {
      headers: { 'X-API-Key': window.Laravel.apiKey }
    });
    fetchApiKeys();
  } catch (e) {
    error.value = 'Failed to update status';
  }
};

const deleteKey = async (id) => {
  if (!confirm('Are you sure? This cannot be undone.')) return;
  try {
    await window.apiClient.delete(`/dashboard/api-keys/${id}`, {
      headers: { 'X-API-Key': window.Laravel.apiKey }
    });
    fetchApiKeys();
  } catch (e) {
    error.value = 'Failed to delete key';
  }
};

const copyKey = (key) => {
  navigator.clipboard.writeText(key);
  alert('Copied to clipboard!');
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

onMounted(() => {
  fetchApiKeys();
  fetchStats();
});
</script>
