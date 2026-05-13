<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $this->setUserSession($request, $user);

        return response()->json([
            'user' => $user,
            'message' => 'User registered successfully',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $validated['username'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $this->setUserSession($request, $user);

        return response()->json([
            'user' => $user,
            'message' => 'Login successful',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = User::with(['orders', 'addresses', 'ratings'])
            ->findOrFail($request->session()->get('user_id'));

        return response()->json($user);
    }

    public function updateMe(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->session()->get('user_id'));

        $validated = $request->validate([
            'username' => 'nullable|string|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->forget(['user_id', 'username']);
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout successful']);
    }

    private function setUserSession(Request $request, User $user): void
    {
        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('username', $user->username);
    }
}