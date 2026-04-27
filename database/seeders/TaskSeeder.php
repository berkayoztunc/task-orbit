<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            // Her ders için 2-3 tane görev oluştur
            Task::factory(rand(2, 3))->create([
                'lesson_id' => $lesson->id,
            ]);
        }
    }
}