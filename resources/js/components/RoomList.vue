<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Rooms</h2>
      <button
        @click="showForm = !showForm"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        {{ showForm ? 'Cancel' : 'Add Room' }}
      </button>
    </div>

    <div v-if="showForm" class="bg-white p-6 rounded-lg shadow mb-6">
      <form @submit.prevent="submitRoom" class="space-y-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name *</label>
            <input v-model="form.name" type="text" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Room Number</label>
            <input v-model="form.room_number" type="text" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Type *</label>
            <input v-model="form.type" type="text" placeholder="Standard, Deluxe, Suite" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Floor</label>
            <input v-model="form.floor" type="number" min="1" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Bed Type</label>
            <select v-model="form.bed_type" class="mt-1 block w-full border rounded px-3 py-2">
              <option value="single">Single</option>
              <option value="double">Double</option>
              <option value="twin">Twin</option>
              <option value="queen">Queen</option>
              <option value="king">King</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Price *</label>
            <input v-model="form.price" type="number" step="0.01" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Capacity *</label>
            <input v-model="form.capacity" type="number" required min="1" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Max Occupancy</label>
            <input v-model="form.max_occupancy" type="number" min="1" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Room Size (sq ft)</label>
            <input v-model="form.room_size" type="number" min="1" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Image URL</label>
            <input v-model="form.image" type="url" placeholder="https://..." class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Available</label>
            <select v-model="form.is_available" class="mt-1 block w-full border rounded px-3 py-2">
              <option :value="true">Yes</option>
              <option :value="false">No</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Amenities</label>
          <div class="flex flex-wrap gap-2 mt-2">
            <label v-for="amenity in amenityOptions" :key="amenity" class="flex items-center space-x-1">
              <input type="checkbox" :value="amenity" v-model="form.amenities" class="rounded" />
              <span>{{ amenity }}</span>
            </label>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea v-model="form.description" rows="3" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          {{ editingId ? 'Update' : 'Create' }}
        </button>
      </form>
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="room in rooms" :key="room.id" class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-xl font-semibold">{{ room.name }}</h3>
            <p class="text-gray-600">
              {{ room.type }}
              <span v-if="room.room_number"> - Room {{ room.room_number }}</span>
            </p>
          </div>
          <span :class="['px-2 py-1 rounded text-sm', room.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
            {{ room.is_available ? 'Available' : 'Booked' }}
          </span>
        </div>
        
        <div class="mt-3 space-y-1 text-gray-700">
          <p><strong>Price:</strong> ${{ room.price }} / night</p>
          <p><strong>Capacity:</strong> {{ room.capacity }} guests</p>
          <p v-if="room.bed_type"><strong>Bed:</strong> {{ room.bed_type }}</p>
          <p v-if="room.floor"><strong>Floor:</strong> {{ room.floor }}</p>
          <p v-if="room.room_size"><strong>Size:</strong> {{ room.room_size }} sq ft</p>
          <p v-if="room.max_occupancy"><strong>Max:</strong> {{ room.max_occupancy }} guests</p>
        </div>

        <div v-if="room.amenities && room.amenities.length" class="mt-2">
          <div class="flex flex-wrap gap-1">
            <span v-for="amenity in room.amenities" :key="amenity" class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
              {{ amenity }}
            </span>
          </div>
        </div>

        <p v-if="room.description" class="text-gray-600 mt-2 text-sm">{{ room.description }}</p>
        
        <div class="mt-4 flex space-x-2">
          <button @click="editRoom(room)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</button>
          <button @click="deleteRoom(room.id)" class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
        </div>
      </div>
    </div>

    <div v-if="rooms.length === 0" class="text-center text-gray-500 py-8">
      No rooms found. Add your first room!
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const rooms = ref([]);
const showForm = ref(false);
const editingId = ref(null);
const error = ref('');

const amenityOptions = ['WiFi', 'AC', 'TV', 'Hot Water', 'Mini Bar', 'Room Service', 'Balcony', 'Sea View', 'City View', 'Coffee Maker'];

const defaultForm = {
  name: '',
  type: '',
  room_number: '',
  floor: 1,
  bed_type: 'double',
  price: '',
  capacity: 2,
  max_occupancy: 2,
  room_size: '',
  image: '',
  amenities: [],
  description: '',
  is_available: true
};

const form = ref({ ...defaultForm });

const fetchRooms = async () => {
  try {
    const response = await window.apiClient.get('/dashboard/rooms');
    rooms.value = response.data;
  } catch (e) {
    error.value = 'Failed to fetch rooms';
  }
};

const submitRoom = async () => {
  try {
    error.value = '';
    const data = { ...form.value };
    if (editingId.value) {
      await window.apiClient.put(`/dashboard/rooms/${editingId.value}`, data);
    } else {
      await window.apiClient.post(`/dashboard/rooms`, data);
    }
    form.value = { ...defaultForm };
    editingId.value = null;
    showForm.value = false;
    fetchRooms();
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to save room';
  }
};

const editRoom = (room) => {
  form.value = {
    name: room.name,
    type: room.type,
    room_number: room.room_number || '',
    floor: room.floor || 1,
    bed_type: room.bed_type || 'double',
    price: room.price,
    capacity: room.capacity,
    max_occupancy: room.max_occupancy || room.capacity,
    room_size: room.room_size || '',
    image: room.image || '',
    amenities: room.amenities || [],
    description: room.description || '',
    is_available: room.is_available
  };
  editingId.value = room.id;
  showForm.value = true;
};

const deleteRoom = async (id) => {
  if (!confirm('Are you sure?')) return;
  try {
    await window.apiClient.delete(`/dashboard/rooms/${id}`);
    fetchRooms();
  } catch (e) {
    error.value = 'Failed to delete room';
  }
};

onMounted(fetchRooms);
</script>
