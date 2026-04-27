<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Task;
use App\Models\User;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_link_an_existing_image_to_a_task()
    {
        // Mevcut bir user ve image oluşturuyoruz
        $user = User::factory()->create();
        $image = Image::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create();

        $payload = [
            'image_id'   => $image->id,
            'media_id'   => $task->id,
            'media_type' => get_class($task),
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('media', [
            'image_id' => $image->id,
            'media_id' => $task->id
        ]);
    }

    public function test_it_can_list_media_with_required_filters()
    {
        $user = User::factory()->create();
        $image = Image::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create();

        // Bir ilişki kuralım
        Media::create([
            'image_id'   => $image->id,
            'media_id'   => $task->id,
            'media_type' => get_class($task),
        ]);

        // Controller index metodu 'media_id' ve 'media_type' zorunlu diyor
        $response = $this->getJson("/api/media?media_id={$task->id}&media_type=" . get_class($task));

        $response->assertStatus(200);
    }
}