<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use App\Models\Profile;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImagePolymorphicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_image_can_belong_to_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        // Bir task için resim oluşturuyoruz
        $image = Image::create([
            'path' => 'tasks/test-task.jpg',
            'user_id' => $user->id,
            'imageable_id' => $task->id,
            'imageable_type' => get_class($task),
        ]);

        $this->assertDatabaseHas('images', [
            'imageable_id' => $task->id,
            'imageable_type' => 'App\Models\Task',
        ]);
    }

    /** @test */
    public function an_image_can_belong_to_a_profile()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create();

        // Bir profil (avatar) için resim oluşturuyoruz
        $image = Image::create([
            'path' => 'avatars/test-profile.jpg',
            'user_id' => $user->id,
            'imageable_id' => $profile->id,
            'imageable_type' => get_class($profile),
        ]);

        $this->assertDatabaseHas('images', [
            'imageable_id' => $profile->id,
            'imageable_type' => 'App\Models\Profile',
        ]);
    }
}