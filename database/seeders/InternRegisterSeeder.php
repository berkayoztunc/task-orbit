<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $internships = \App\Models\Internship::all();
        $internProfiles = \App\Models\Profile::whereHas('role', function($q){
        $q->where('name', 'Intern');
    })->get();

    foreach ($internProfiles as $profile) {
        \App\Models\InternRegister::factory()->create([
            'profile_id' => $profile->id,
            'internship_id' => $internships->random()->id,
        ]);
    }
    }
}
