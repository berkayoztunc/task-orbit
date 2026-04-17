<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internship;

class InternshipSeeder extends Seeder
{
    public function run(): void
    {
       
    $companies = \App\Models\Company::all();
    foreach ($companies as $company) {
        \App\Models\Internship::factory(2)->create([
            'company_id' => $company->id
        ]);
    }
    }
}