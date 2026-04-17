<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $internships = \App\Models\Internship::all();
    $mentorProfiles = \App\Models\Profile::whereHas('role', function($q){
        $q->where('name', 'Mentor');
    })->get();

    foreach ($internships as $internship) {
        \App\Models\Lesson::factory(3)->create([
            'internship_id' => $internship->id,
            'profile_id' => $mentorProfiles->random()->id, 
        ]);
    }
    }
}
