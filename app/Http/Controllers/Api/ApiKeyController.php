<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index(): JsonResponse
    {
        $keys = ApiKey::orderBy('created_at', 'desc')->get();

        return response()->json($keys);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days' => 'nullable|integer|min:1',
        ]);

        $days = $validated['days'] ?? null;
        $apiKey = ApiKey::generate($validated['name'], $days);

        return response()->json($apiKey, 201);
    }

    public function update(Request $request, ApiKey $apiKey): JsonResponse
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $apiKey->update($validated);

        return response()->json($apiKey);
    }

    public function destroy(ApiKey $apiKey): JsonResponse
    {
        $apiKey->delete();

        return response()->json(['message' => 'API key deleted successfully']);
    }
}
