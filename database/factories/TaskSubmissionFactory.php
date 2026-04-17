<?php

namespace Database\Factories;

use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskSubmission>
 */
class TaskSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intern_register_id' => \App\Models\InternRegister::factory(),
            'task_id' => \App\Models\Task::factory(),
            'submissions' => fake()->url(), 
            'point' => fake()->numberBetween(0, 100),
            'status' => fake()->boolean(),
        ];
    }
}
