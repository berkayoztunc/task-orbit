<?php

namespace App\Http\Controllers;

use App\Models\Command;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index()
    {
        // Tüm komutları al
        $commands = Command::all();

        // Komutları JSON formatında döndür
        return response()->json([
            'status' => 'success',
            'message' => 'Commands retrieved successfully',
            'data' => $commands,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:60|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Komutu oluştur, veritabanına kaydet. Buradaki only() metodu, sadece belirtilen alanları alır ve diğerlerini göz ardı eder.
        $command = Command::create($request->only('message', 'user_id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Command created successfully',
            'data' => $command,
        ], 201);
    }

    public function show(Command $command)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Command retrieved successfully',
            'data' => $command,
        ]);
    }

    public function destroy(Command $command)
    {
        $command->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Command deleted successfully',
        ], 200);
    }
}
