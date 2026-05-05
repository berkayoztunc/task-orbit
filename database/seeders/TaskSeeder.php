<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $lessons = \App\Models\Lesson::all();
    foreach ($lessons as $lesson) {
        \App\Models\Task::factory()->create(['lesson_id' => $lesson->id]);
    }
}
}
