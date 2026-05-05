<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Image;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $images = Image::all();

        foreach ($images as $image) {
            Media::create([
                'image_id' => $image->id,
                'media_id' => $image->imageable_id,
                'media_type' => $image->imageable_type,
            ]);
        }
    }
}