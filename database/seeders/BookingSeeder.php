<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();

        $bookings = [
            [
                'room_id' => $rooms[0]->id,
                'guest_name' => 'John Smith',
                'guest_email' => 'john@example.com',
                'guest_phone' => '+1234567890',
                'check_in' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'guests_count' => 1,
                'status' => 'confirmed',
                'notes' => 'Early check-in requested',
            ],
            [
                'room_id' => $rooms[1]->id,
                'guest_name' => 'Sarah Johnson',
                'guest_email' => 'sarah@example.com',
                'guest_phone' => '+1987654321',
                'check_in' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'guests_count' => 2,
                'status' => 'pending',
                'notes' => '',
            ],
            [
                'room_id' => $rooms[3]->id,
                'guest_name' => 'Michael Brown',
                'guest_email' => 'michael@example.com',
                'guest_phone' => '+1122334455',
                'check_in' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'guests_count' => 2,
                'status' => 'confirmed',
                'notes' => 'Anniversary celebration',
            ],
            [
                'room_id' => $rooms[4]->id,
                'guest_name' => 'Emily Davis',
                'guest_email' => 'emily@example.com',
                'guest_phone' => '+1555666777',
                'check_in' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'guests_count' => 2,
                'status' => 'completed',
                'notes' => 'VIP guest',
            ],
            [
                'room_id' => $rooms[5]->id,
                'guest_name' => 'David Wilson',
                'guest_email' => 'david@example.com',
                'guest_phone' => '+1888999000',
                'check_in' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'guests_count' => 4,
                'status' => 'confirmed',
                'notes' => 'Family vacation',
            ],
        ];

        foreach ($bookings as $booking) {
            $room = Room::find($booking['room_id']);
            $checkIn = Carbon::parse($booking['check_in']);
            $checkOut = Carbon::parse($booking['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            $booking['total_price'] = $room->price * $nights;

            Booking::create($booking);
        }
    }
}
