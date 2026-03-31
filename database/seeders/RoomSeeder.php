<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Standard Single Room',
                'type' => 'Standard',
                'room_number' => '101',
                'floor' => 1,
                'bed_type' => 'single',
                'price' => 50.00,
                'capacity' => 1,
                'max_occupancy' => 1,
                'room_size' => 200,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC'],
                'description' => 'Comfortable single room with essential amenities.',
            ],
            [
                'name' => 'Deluxe Double Room',
                'type' => 'Deluxe',
                'room_number' => '201',
                'floor' => 2,
                'bed_type' => 'double',
                'price' => 85.00,
                'capacity' => 2,
                'max_occupancy' => 2,
                'room_size' => 350,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Room Service'],
                'description' => 'Spacious deluxe room with city view.',
            ],
            [
                'name' => 'Executive Twin Room',
                'type' => 'Deluxe',
                'room_number' => '202',
                'floor' => 2,
                'bed_type' => 'twin',
                'price' => 90.00,
                'capacity' => 2,
                'max_occupancy' => 2,
                'room_size' => 380,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Mini Bar', 'Room Service'],
                'description' => 'Modern twin room with work desk and mini bar.',
            ],
            [
                'name' => 'Premium Queen Room',
                'type' => 'Premium',
                'room_number' => '301',
                'floor' => 3,
                'bed_type' => 'queen',
                'price' => 120.00,
                'capacity' => 2,
                'max_occupancy' => 3,
                'room_size' => 450,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Mini Bar', 'Room Service', 'Balcony', 'Sea View'],
                'description' => 'Elegant queen room with sea view and private balcony.',
            ],
            [
                'name' => 'Royal Suite',
                'type' => 'Suite',
                'room_number' => '401',
                'floor' => 4,
                'bed_type' => 'king',
                'price' => 250.00,
                'capacity' => 4,
                'max_occupancy' => 4,
                'room_size' => 800,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Mini Bar', 'Room Service', 'Balcony', 'Sea View', 'Coffee Maker'],
                'description' => 'Luxurious suite with separate living area and panoramic sea view.',
            ],
            [
                'name' => 'Family Room',
                'type' => 'Family',
                'room_number' => '102',
                'floor' => 1,
                'bed_type' => 'double',
                'price' => 110.00,
                'capacity' => 4,
                'max_occupancy' => 5,
                'room_size' => 500,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Room Service'],
                'description' => 'Spacious family room perfect for families.',
            ],
            [
                'name' => 'Economy Room',
                'type' => 'Economy',
                'room_number' => '001',
                'floor' => 1,
                'bed_type' => 'single',
                'price' => 35.00,
                'capacity' => 1,
                'max_occupancy' => 1,
                'room_size' => 150,
                'is_available' => true,
                'amenities' => ['WiFi'],
                'description' => 'Budget-friendly room with basic amenities.',
            ],
            [
                'name' => 'Honeymoon Suite',
                'type' => 'Suite',
                'room_number' => '501',
                'floor' => 5,
                'bed_type' => 'king',
                'price' => 300.00,
                'capacity' => 2,
                'max_occupancy' => 2,
                'room_size' => 650,
                'is_available' => true,
                'amenities' => ['WiFi', 'TV', 'AC', 'Hot Water', 'Mini Bar', 'Room Service', 'Balcony', 'Sea View', 'Coffee Maker'],
                'description' => 'Romantic suite with special honeymoon amenities and flower arrangements.',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
