<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
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
            'lesson_id' => \App\Models\Lesson::factory(),
            'status' => fake()->boolean(),
        ];
    }
}
