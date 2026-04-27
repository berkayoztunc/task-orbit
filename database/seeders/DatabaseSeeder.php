<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
        // Temel Yapı
        AdminSeeder::class,
        RoleSeeder::class,
        UserSeeder::class,
        CompanySeeder::class,
        ProfileSeeder::class,
        
        // Operasyonel Yapı
        InternshipSeeder::class,
        LessonSeeder::class,
        TaskSeeder::class,
        InternRegisterSeeder::class,
        AttendanceSeeder::class,
        TaskSubmissionSeeder::class,
        
        // Etkileşim ve Medya (En Son)
        CommandSeeder::class,
        CommantableSeeder::class,
        ImageSeeder::class,
        MediaSeeder::class,
    ]);
    }
}
