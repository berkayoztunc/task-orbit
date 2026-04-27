<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_upload_an_image()
    {
        Storage::fake('public');
        
       
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id, 
            'image'   => UploadedFile::fake()->image('avatar.jpg'),
            'title'   => 'Stajyer Profil Fotoğrafı'
        ];

        $response = $this->postJson('/api/images', $payload);

        $response->assertStatus($response->status() === 201 ? 201 : 200);
    }
}