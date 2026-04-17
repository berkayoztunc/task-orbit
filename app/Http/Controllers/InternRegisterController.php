<?php

namespace App\Http\Controllers;

use App\Models\InternRegister;
use App\Models\Internship;
use Illuminate\Http\Request;

class InternRegisterController extends Controller
{
    public function index()
    {
        $internRegisters = InternRegister::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Intern registers retrieved successfully',
            'data' => $internRegisters,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'internship_id' => 'required|exists:internships,id',
        ]);

        // Create the intern using the validated data
        $internRegister = InternRegister::create([
            'profile_id' => $validated['profile_id'],
            'internship_id' => $validated['internship_id'],
            'status' => false,
            'message' => 'Bekliyor',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Intern registered successfully',
            'data' => $internRegister,
        ]);
    }

    public function show(InternRegister $internRegister)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Intern register retrieved successfully',
            'data' => $internRegister,
        ]);
    }

    public function update(Request $request, InternRegister $internRegister)
    {
        $validated = $request->validate([
            'status' => 'required|boolean',
            'message' => 'required|string',
        ]);

        $internRegister->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Intern register updated successfully',
            'data' => $internRegister,
        ]);
    }

    public function indexByInternship(Internship $internship)
    {
        $internRegisters = $internship->internRegisters()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Intern registers for the internship retrieved successfully',
            'data' => $internRegisters,
        ]);
    }
}
