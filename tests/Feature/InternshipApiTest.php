<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_internships()
    {
        Internship::factory()->count(2)->create();

        $response = $this->getJson('/api/internships');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');
    }

    public function test_it_can_create_an_internship_program()
    {
        $company = Company::factory()->create();

        $payload = [
            'company_id' => $company->id,
            'title'      => 'Yazılım Geliştirme Stajı 2026',
            'description'=> 'Geniousoft bünyesinde 3 aylık backend staj programı.',
            'start_date' => '2026-06-01',
            'end_date'   => '2026-09-01',
            'status'     => '1'
        ];

        $response = $this->postJson('/api/internships', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'Yazılım Geliştirme Stajı 2026');

        $this->assertDatabaseHas('internships', [
            'title' => 'Yazılım Geliştirme Stajı 2026'
        ]);
    }

    public function test_it_can_update_internship_details()
    {
        $internship = Internship::factory()->create(['title' => 'Eski Başlık']);

        $response = $this->putJson("/api/internships/{$internship->id}", [
            'title' => 'Güncel Başlık'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('internships', [
            'id'    => $internship->id,
            'title' => 'Güncel Başlık'
        ]);
    }
}