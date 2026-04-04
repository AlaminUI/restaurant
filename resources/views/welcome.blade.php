<?php
use App\Models\ApiKey;

$apiKey = ApiKey::where('is_active', true)
    ->where(function ($query) {
        $query->whereNull('expires_at')
            ->orWhere('expires_at', '>', now());
    })
    ->first();
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Restaurant & Room Booking</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            window.Laravel = {
                isAuthenticated: {{ Auth::check() ? 'true' : 'false' }},
                user: @json(Auth::user()),
                apiKey: '{{ $apiKey ? $apiKey->key : '' }}',
            };
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>