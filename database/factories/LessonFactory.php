<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
public function definition(): array
{
    return [
        'title' => fake()->sentence(3),
        'internship_id' => \App\Models\Internship::factory(),
        'start_date' => fake()->dateTimeBetween('now', '+1 week'),
        'end_date' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
        'description' => fake()->text(),
        'content' => fake()->paragraph(),
        'profile_id' => \App\Models\Profile::factory(), 
    ];
}
}
