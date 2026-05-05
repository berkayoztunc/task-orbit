<?php

namespace Tests\Feature;

use App\Models\TaskSubmission;
use App\Models\Task;
use App\Models\InternRegister; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSubmissionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_task_submissions()
    {
        $task = Task::factory()->create();
        TaskSubmission::factory()->count(2)->create(['task_id' => $task->id]);

        $response = $this->getJson("/api/task-submissions?task_id={$task->id}");

        $response->assertStatus(200);
    }

    
    public function test_it_can_submit_a_task()
    {
        $task = Task::factory()->create();
        $internRegister = InternRegister::factory()->create();

        $payload = [
            'task_id'            => $task->id,
            'intern_register_id' => $internRegister->id,
            'submissions'        => 'İşte görev teslim içeriğim.', 
        ];

        $response = $this->postJson('/api/task-submissions', $payload);

        $response->assertStatus($response->status() === 201 ? 201 : 200);

        $this->assertDatabaseHas('task_submissions', [
            'task_id'            => $task->id,
            'intern_register_id' => $internRegister->id
        ]);
    }
}