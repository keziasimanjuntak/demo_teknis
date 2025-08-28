<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // CREATE (POST /users)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|in:male,female',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // READ by ID (GET /users/{id})
    public function show($id)
    {
        $user = User::findOrFail($id);
        Log::info("GET /users/{$id}", $user->toArray());
        return response()->json($user);
    }

    // READ all (GET /users)
    public function index()
    {
        $users = User::all();
        Log::info("GET /users", $users->toArray());
        return response()->json($users);
    }

    // UPDATE (PUT /users/{id})
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'gender' => 'sometimes|in:male,female',
            'password' => 'nullable|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        Log::info("PUT /users/{$id}", $user->toArray());

        return response()->json($user);
    }

    // DELETE (DELETE /users/{id})
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Log::info("DELETE /users/{$id}");

        return response()->json(['message' => 'User deleted successfully']);
    }
}
