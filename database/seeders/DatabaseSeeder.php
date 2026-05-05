<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            ProfileSeeder::class,
            InternshipSeeder::class,
            LessonSeeder::class,
            TaskSeeder::class,
            AttendanceSeeder::class,
            TaskSubmissionSeeder::class,
    ]);
    }
}