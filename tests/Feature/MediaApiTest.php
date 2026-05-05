<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    
     public function test_task_can_have_an_associated_image()
    {
        Storage::fake('public');

        $user = User::factory()->create(); // Kullanıcı oluştur
        $task = Task::factory()->create();
        $file = UploadedFile::fake()->image('task_image.jpg');

        $task->images()->create([
            'path' => $file->store('tasks', 'public'),
            'label' => 'Task Cover',
            'user_id' => $user->id // Eksik olan parça buydu!
        ]);

        $this->assertDatabaseHas('images', [
            'imageable_id' => $task->id,
            'imageable_type' => Task::class,
            'user_id' => $user->id,
        ]);
        
        Storage::disk('public')->assertExists('tasks/' . $file->hashName());
    }

    public function test_user_can_have_a_profile_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $user->images()->create([
            'path' => $file->store('avatars', 'public'),
            'label' => 'Profile Picture',
            'user_id' => $user->id // Buraya da ekliyoruz
        ]);

        $this->assertDatabaseHas('images', [
            'imageable_id' => $user->id,
            'imageable_type' => User::class,
            'user_id' => $user->id,
        ]);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}