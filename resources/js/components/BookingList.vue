<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Bookings</h2>
      <button
        @click="showForm = !showForm"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        {{ showForm ? 'Cancel' : 'New Booking' }}
      </button>
    </div>

    <div v-if="showForm" class="bg-white p-6 rounded-lg shadow mb-6">
      <form @submit.prevent="submitBooking" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Guest Name</label>
            <input v-model="form.guest_name" type="text" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Guest Email</label>
            <input v-model="form.guest_email" type="email" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input v-model="form.guest_phone" type="text" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Room</label>
            <select v-model="form.room_id" required class="mt-1 block w-full border rounded px-3 py-2">
              <option value="">Select a room</option>
              <option v-for="room in availableRooms" :key="room.id" :value="room.id">
                {{ room.name }} - ${{ room.price }}/night
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Check In</label>
            <input v-model="form.check_in" type="date" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Check Out</label>
            <input v-model="form.check_out" type="date" required class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Guests Count</label>
            <input v-model="form.guests_count" type="number" required min="1" class="mt-1 block w-full border rounded px-3 py-2" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Notes</label>
          <textarea v-model="form.notes" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          {{ editingId ? 'Update' : 'Create Booking' }}
        </button>
      </form>
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ success }}
    </div>

    <div class="space-y-4">
      <div v-for="booking in bookings" :key="booking.id" class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-xl font-semibold">{{ booking.guest_name }}</h3>
            <p class="text-gray-600">{{ booking.guest_email }}</p>
          </div>
          <span :class="['px-2 py-1 rounded text-sm', statusColors[booking.status] || 'bg-gray-100']">
            {{ booking.status }}
          </span>
        </div>
        <div class="mt-2 text-gray-700">
          <p><strong>Room:</strong> {{ booking.room?.name }}</p>
          <p><strong>Check In:</strong> {{ booking.check_in }}</p>
          <p><strong>Check Out:</strong> {{ booking.check_out }}</p>
          <p><strong>Guests:</strong> {{ booking.guests_count }}</p>
          <p><strong>Total:</strong> ${{ booking.total_price }}</p>
        </div>
        <div v-if="booking.notes" class="mt-2 text-gray-600">
          <p><strong>Notes:</strong> {{ booking.notes }}</p>
        </div>
        <div class="mt-4 flex space-x-2">
          <select
            :value="booking.status"
            @change="updateStatus(booking.id, $event.target.value)"
            class="border rounded px-2 py-1 text-sm"
          >
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <button @click="editBooking(booking)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</button>
          <button @click="deleteBooking(booking.id)" class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
        </div>
      </div>
    </div>

    <div v-if="bookings.length === 0" class="text-center text-gray-500 py-8">
      No bookings found. Create your first booking!
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const bookings = ref([]);
const rooms = ref([]);
const showForm = ref(false);
const editingId = ref(null);
const error = ref('');
const success = ref('');

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800'
};

const form = ref({
  guest_name: '',
  guest_email: '',
  guest_phone: '',
  room_id: '',
  check_in: '',
  check_out: '',
  guests_count: 1,
  notes: ''
});

const availableRooms = computed(() => rooms.value.filter(r => r.is_available));

const fetchBookings = async () => {
  try {
    const response = await axios.get('/api/dashboard/bookings');
    bookings.value = response.data;
  } catch (e) {
    error.value = 'Failed to fetch bookings';
  }
};

const fetchRooms = async () => {
  try {
    const response = await axios.get('/api/dashboard/rooms');
    rooms.value = response.data;
  } catch (e) {
    error.value = 'Failed to fetch rooms';
  }
};

const submitBooking = async () => {
  try {
    error.value = '';
    success.value = '';
    if (editingId.value) {
      await axios.put(`/api/dashboard/bookings/${editingId.value}`, form.value);
      success.value = 'Booking updated successfully';
    } else {
      await axios.post('/api/dashboard/bookings', form.value);
      success.value = 'Booking created successfully';
    }
    form.value = { guest_name: '', guest_email: '', guest_phone: '', room_id: '', check_in: '', check_out: '', guests_count: 1, notes: '' };
    editingId.value = null;
    showForm.value = false;
    fetchBookings();
    setTimeout(() => success.value = '', 3000);
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to save booking';
  }
};

const editBooking = (booking) => {
  form.value = {
    guest_name: booking.guest_name,
    guest_email: booking.guest_email,
    guest_phone: booking.guest_phone,
    room_id: booking.room_id,
    check_in: booking.check_in,
    check_out: booking.check_out,
    guests_count: booking.guests_count,
    notes: booking.notes
  };
  editingId.value = booking.id;
  showForm.value = true;
};

const updateStatus = async (id, status) => {
  try {
    await axios.patch(`/api/dashboard/bookings/${id}`, { status });
    fetchBookings();
  } catch (e) {
    error.value = 'Failed to update status';
  }
};

const deleteBooking = async (id) => {
  if (!confirm('Are you sure?')) return;
  try {
    await axios.delete(`/api/dashboard/bookings/${id}`);
    fetchBookings();
  } catch (e) {
    error.value = 'Failed to delete booking';
  }
};

onMounted(() => {
  fetchBookings();
  fetchRooms();
});
</script>
