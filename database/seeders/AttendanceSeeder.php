<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\InternRegister;
use App\Models\Lesson; // Ders modelini ekledik
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $registers = InternRegister::all();
        $lessons = Lesson::all();

        if ($lessons->isEmpty()) {
            return; // Eğer hiç ders yoksa hata almamak için
        }

        foreach ($registers as $register) {
            for ($i = 0; $i < 5; $i++) {
                Attendance::create([
                    'intern_register_id' => $register->id,
                    'lesson_id' => $lessons->random()->id, // Eksik olan lesson_id'yi ekledik!
                    'status' => rand(0, 1),
                    'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                ]);
            }
        }
    }
}