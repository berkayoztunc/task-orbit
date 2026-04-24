<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSubmissionSeeder extends Seeder
{
    public function run(): void
{
    $tasks = \App\Models\Task::all();
    $registers = \App\Models\InternRegister::where('message', 'Onaylandı')->get();

    foreach ($tasks as $task) {
        // Her görev için onaylı stajyerlerden rastgele bir teslimat oluşturalım
        foreach ($registers as $register) {
            \App\Models\TaskSubmission::factory()->create([
                'task_id' => $task->id,
                'intern_register_id' => $register->id,
            ]);
        }
    }
}
}
