<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\InternRegister;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $registers = InternRegister::all();
        $lessons = Lesson::all();

        if ($lessons->isEmpty()) {
            return;
        }

        foreach ($registers as $register) {
            for ($i = 0; $i < 5; $i++) {
                Attendance::create([
                    'intern_register_id' => $register->id,
                    'lesson_id' => $lessons->random()->id,
                    'status' => rand(0, 1),
                    'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                ]);
            }
        }
    }
}