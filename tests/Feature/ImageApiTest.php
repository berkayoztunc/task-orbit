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
    $task = \App\Models\Task::factory()->create();
    $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');

    $payload = [
        'image' => $file,
        'user_id' => 1,
        'imageable_id' => $task->id,
        'imageable_type' => get_class($task), 
    ];

    $response = $this->postJson('/api/images', $payload);

    $response->assertStatus(201);
}
}