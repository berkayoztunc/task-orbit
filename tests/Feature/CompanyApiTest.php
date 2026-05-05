<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_companies()
    {
        Company::factory()->count(3)->create();
        $response = $this->getJson('/api/companies');
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_it_can_create_a_company()
    {
        $payload = ['title' => 'Geniousoft'];
        $response = $this->postJson('/api/companies', $payload);
        $response->assertStatus(201)->assertJsonPath('data.title', 'Geniousoft');
    }

    public function test_it_fails_to_create_company_with_short_title()
    {
        $payload = ['title' => 'Ge'];
        $response = $this->postJson('/api/companies', $payload);
        $response->assertStatus(422);
    }
}