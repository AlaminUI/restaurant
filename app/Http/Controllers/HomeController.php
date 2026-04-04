<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $apiKey = ApiKey::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        return view('welcome', [
            'isAuthenticated' => Auth::check(),
            'user' => Auth::user(),
            'apiKey' => $apiKey ? $apiKey->key : null,
            'stats' => [
                'totalRooms' => Room::count(),
                'availableRooms' => Room::where('is_available', true)->count(),
                'totalBookings' => Booking::count(),
            ],
            'rooms' => Room::all(),
            'bookings' => Booking::with('room')->get(),
            'apiKeys' => ApiKey::all(),
        ]);
    }
}
