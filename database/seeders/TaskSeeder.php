<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            
            Task::factory(rand(2, 3))->create([
                'lesson_id' => $lesson->id,
            ]);
        }
    }
}