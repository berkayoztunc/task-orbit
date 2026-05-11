<?php

namespace Tests\Feature;

use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSubmissionPointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_instructor_can_grade_submission()
    {
        $submission = TaskSubmission::factory()->create(['point' => 0]);

        $response = $this->patchJson("/api/task-submissions/{$submission->id}", [
            'point' => 95,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('task_submissions', [
            'id' => $submission->id,
            'point' => 95,
        ]);
    }
}
