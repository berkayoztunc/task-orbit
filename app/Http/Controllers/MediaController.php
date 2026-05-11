<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
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
            'data' => $media,
        ], 201);
    }

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
            'data' => $media,
        ]);
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Media unlinked successfully',
        ]);
    }
}
