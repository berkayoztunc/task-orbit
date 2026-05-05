<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Profile;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // Önce resmi yükleyen bir kullanıcı bulalım (UserSeeder'dan gelmeli)
        $user = User::first();
        
        if (!$user) {
            return; // Kullanıcı yoksa hata vermemesi için koruma
        }

        $profiles = Profile::all();
        $tasks = Task::all();

        // 1. Profil Resimleri (Avatarlar)
        foreach ($profiles as $profile) {
            Image::create([
                'path' => 'avatars/default-user.png',
                'user_id' => $user->id,
                'imageable_id' => $profile->id,
                'imageable_type' => Profile::class, // 'App\Models\Profile' stringini döner
            ]);
        }

        // 2. Görev Resimleri (Bazı rastgele görevlere görsel ekleyelim)
        if ($tasks->count() > 0) {
            foreach ($tasks->random(min(5, $tasks->count())) as $task) {
                Image::create([
                    'path' => 'tasks/task-proof.jpg',
                    'user_id' => $user->id,
                    'imageable_id' => $task->id,
                    'imageable_type' => Task::class, // 'App\Models\Task' stringini döner
                ]);
            }
        }
    }
}