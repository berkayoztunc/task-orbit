<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Profiles retrieved successfully',
            'data' => $roles
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'comany_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        $role = Role::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile created successfully',
            'data' => $role
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Profile retrieved successfully',
            'data' => $role
        ], 200);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer',
        ]);

        $role->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $role
        ], 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile deleted successfully'
        ], 200);
    }
}
