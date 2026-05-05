<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_tasks()
    {
        
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');
    }

    public function test_it_can_create_a_task()
    {
        $company = \App\Models\Company::factory()->create();
        $lesson = \App\Models\Lesson::factory()->create(); 

        $payload = [
            'company_id' => $company->id,
            'lesson_id'  => $lesson->id, 
            'title'      => 'Yeni Proje Taskı',
            'description'=> 'Bu görev Geniousoft projesi için oluşturuldu.',
            'status'     => 'pending'
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'Yeni Proje Taskı');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Yeni Proje Taskı',
            'lesson_id' => $lesson->id
        ]);
    }

    public function test_it_can_show_task_details()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $task->id);
    }
}