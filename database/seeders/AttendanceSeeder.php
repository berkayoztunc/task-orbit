<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
   public function run(): void
{
    $registers = \App\Models\InternRegister::all();
    
    foreach ($registers as $register) {
        $lessons = \App\Models\Lesson::where('internship_id', $register->internship_id)->get();
        
        foreach ($lessons as $lesson) {
            \App\Models\Attendance::factory()->create([
                'intern_register_id' => $register->id,
                'lesson_id' => $lesson->id,
                'status' => true
            ]);
        }
    }
}
}
