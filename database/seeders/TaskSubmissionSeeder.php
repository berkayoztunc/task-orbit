<?php

namespace Database\Seeders;

use App\Models\TaskSubmission;
use App\Models\InternRegister;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sadece onaylanmış stajyerleri almak daha güvenli olabilir
        $registers = InternRegister::all(); 
        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            return;
        }

        foreach ($registers as $register) {
            TaskSubmission::factory()->create([
                'intern_register_id' => $register->id,
                'task_id' => $tasks->random()->id,
                'point' => rand(50, 100),
                'status' => 1
            ]);
        }
    }
}