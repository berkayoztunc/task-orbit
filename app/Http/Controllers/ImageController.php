<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    // Dosyayı storage'a kaydeder, image kaydı oluşturur.
    // storage/app/public/uploads klasörüne gider.
    // user_id → kim yükledi bilgisi, users tablosuna bağlı.
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|max:10240', // max 10MB
            'user_id' => 'required|exists:users,id'
        ]);

        // Dosyayı storage/app/public/uploads klasörüne kaydet
        // store() → benzersiz isim verir, path'i döner (örn: uploads/abc123.jpg)
        $path = $request->file('image')->store('uploads', 'public');

        $image = Image::create([
            'path' => $path,
            'user_id' => $request->user_id,
            'imageable_id' => 'required',
            'imageable_type' => 'required'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Image uploaded successfully',
            'data' => $image
        ], 201);
    }

    // Image'ı siler — storage'daki dosyayı da siler.
    // Sadece kaydı silmek yetmez, fiziksel dosya da temizlenmeli.
    public function destroy(Image $image)
    {
        // Storage'daki fiziksel dosyayı sil
        \Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Image deleted successfully'
        ]);
    }
}
