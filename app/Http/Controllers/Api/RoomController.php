<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function stats(): JsonResponse
    {
        $rooms = Room::all();
        $bookings = Booking::all();

        return response()->json([
            'totalRooms' => $rooms->count(),
            'availableRooms' => $rooms->where('is_available', true)->count(),
            'totalBookings' => $bookings->count(),
            'pendingBookings' => $bookings->where('status', 'pending')->count(),
            'confirmedBookings' => $bookings->where('status', 'confirmed')->count(),
            'completedBookings' => $bookings->where('status', 'completed')->count(),
            'revenue' => $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Room::query();

        if ($request->has('available')) {
            $query->where('is_available', $request->boolean('available'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $rooms = $query->get();

        return response()->json($rooms);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_available' => 'boolean',
            'room_number' => 'nullable|string|unique:rooms',
            'floor' => 'nullable|integer|min:1',
            'bed_type' => 'nullable|string',
            'room_size' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'image' => 'nullable|string',
            'max_occupancy' => 'nullable|integer|min:1',
        ]);

        $room = Room::create($validated);

        return response()->json($room, 201);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json($room);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'nullable|string',
            'is_available' => 'boolean',
            'room_number' => 'sometimes|string|unique:rooms,room_number,'.$room->id,
            'floor' => 'sometimes|integer|min:1',
            'bed_type' => 'sometimes|string',
            'room_size' => 'sometimes|integer|min:1',
            'amenities' => 'nullable|array',
            'image' => 'nullable|string',
            'max_occupancy' => 'sometimes|integer|min:1',
        ]);

        $room->update($validated);

        return response()->json($room);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }

    public function availability(Request $request, Room $room): JsonResponse
    {
        $startDate = $request->query('start_date', now()->toDateString());
        $endDate = $request->query('end_date', now()->addDays(30)->toDateString());

        $bookings = Booking::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('check_in', '<=', $startDate)
                            ->where('check_out', '>=', $endDate);
                    });
            })
            ->get();

        return response()->json([
            'room' => $room,
            'booked_dates' => $bookings->map(fn ($b) => [
                'check_in' => $b->check_in,
                'check_out' => $b->check_out,
            ]),
            'is_available' => $bookings->isEmpty(),
        ]);
    }
}
