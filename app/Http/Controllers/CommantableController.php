<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commantable;

class CommantableController extends Controller
{
    public function index()
    {
        $commantables = Commantable::all();

        return response()->json([
            'success' => true,
            'data' => $commantables,
        ]);
    }

    // Yeni commantable kaydı oluştur
    // Bir yorumu bir modele bağlar (ders, görev, staj gibi)
    // command_id → hangi yorum
    // commantable_id → hangi kaydın id'si
    // commantable_type → hangi model (Lesson, Task, Internship)
    public function store(Request $request)
    {
        $request->validate([
            'command_id' => 'required|integer|exists:commands,id',
            'commantable_id' => 'required|integer',
            'commantable_type' => 'required|string',
        ]);

        // Sadece gerekli alanları alıp commantable kaydı oluştur
        $commantable = Commantable::create($request->only('command_id', 'commantable_id', 'commantable_type'));

        return response()->json([
            'success' => true,
            'data' => $commantable,
        ], 201);
    }

    // Commantable kaydını sil
    // Yorumun bir modelle olan bağlantısını kaldırır
    // Route Model Binding ile Laravel otomatik olarak kaydı bulur
    // Bulunamazsa otomatik 404 döner
    public function destroy(Commantable $commantable)
    {
        // Kaydı veritabanından sil
        $commantable->delete();

        // 200 ile başarı mesajı döndür
        return response()->json([
            'success' => true,
            'message' => 'Commantable deleted successfully',
        ], 200);
    }
}