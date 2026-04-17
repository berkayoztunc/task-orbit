<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // Bir image'ı herhangi bir modele bağlar.
    // Örn: yüklenen resmi bir Task'a veya Profile'a bağlamak için kullanılır.
    // media_id → bağlanacak modelin id'si (örn: task'ın id'si)
    // media_type → hangi model olduğu (örn: "App\Models\Task")
    // image_id → images tablosundaki kayıt, daha önce ImageController ile yüklendi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_id'   => 'required|exists:images,id',
            'media_id'   => 'required|integer',
            'media_type' => 'required|string',
        ]);

        $media = Media::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Media linked successfully',
            'data' => $media
        ], 201);
    }

    // Bir modele bağlı tüm image'ları getirir.
    // Örn: "Bu task'a bağlı resimler neler?" sorusunun cevabı.
    public function index(Request $request)
    {
        $validated = $request->validate([
            'media_id'   => 'required|integer',
            'media_type' => 'required|string',
        ]);

        $media = Media::with('image')
            ->where('media_id', $validated['media_id'])
            ->where('media_type', $validated['media_type'])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Media retrieved successfully',
            'data' => $media
        ]);
    }

    // Bağlantıyı koparır — image silinmez, sadece modelle ilişkisi kaldırılır.
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Media unlinked successfully'
        ]);
    }
}
