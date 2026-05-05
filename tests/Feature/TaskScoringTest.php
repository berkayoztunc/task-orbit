<?php

namespace Tests\Feature;

use App\Models\TaskSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskScoringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_instructor_can_set_score_for_submission()
    {
        $submission = TaskSubmission::factory()->create(['point' => 0]);

        $response = $this->patchJson("/api/task-submissions/{$submission->id}", [
            'point' => 90
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('task_submissions', [
            'id' => $submission->id,
            'point' => 90
        ]);
    }
}