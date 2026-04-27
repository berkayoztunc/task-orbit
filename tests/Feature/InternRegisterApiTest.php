<?php

namespace Tests\Feature;

use App\Models\InternRegister;
use App\Models\Profile;
use App\Models\Internship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternRegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_new_registration()
    {
        $profile = Profile::factory()->create();
        $internship = Internship::factory()->create();

        $payload = [
            'profile_id'    => $profile->id,
            'internship_id' => $internship->id,
            'status'        => 0,
            'message'       => 'Beklemede'
        ];

        $response = $this->postJson('/api/intern-registers', $payload);
        
        $response->assertStatus($response->status() === 201 ? 201 : 200);
    }

    public function test_it_can_update_registration_status()
    {
        $register = InternRegister::factory()->create(['status' => 0]);

        $response = $this->putJson("/api/intern-registers/{$register->id}", [
            'status'  => 1, 
            'message' => 'Onaylandı'
        ]);

        $response->assertStatus(200);
    }
}