<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\InternRegister;
use App\Models\Lesson;
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
            'intern_register_id' => InternRegister::factory(),
            'lesson_id' => Lesson::factory(),
            'status' => fake()->boolean(),
            'date' => fake()->date(),
        ];
    }
}
