<?php

namespace Database\Factories;

use App\Models\InternRegister;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InternRegister>
 */
class InternRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $status = fake()->randomElement(['Bekliyor', 'Onaylandı', 'Reddedildi']);
    
    return [
        'profile_id' => \App\Models\Profile::factory(),
        'internship_id' => \App\Models\Internship::factory(),
        'message' => $status,
        'status' => ($status === 'Onaylandı') ? true : false,
    ];
}
}
