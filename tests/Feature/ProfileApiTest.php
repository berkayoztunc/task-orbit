<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_profiles()
    {
        Profile::factory()->count(2)->create();

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200);
    }

    public function test_it_can_create_a_profile()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $role = Role::factory()->create();

        
        $payload = [
            'user_id'    => $user->id,
            'company_id' => $company->id,
            'role_id'    => $role->id,
        ];

        $response = $this->postJson('/api/profiles', $payload);

        // Controller 201 dönüyor
        $response->assertStatus(201);

        
        $this->assertDatabaseHas('profiles', [
            'user_id'    => $user->id,
            'company_id' => $company->id,
            'role_id'    => $role->id,
        ]);
    }

    public function test_it_can_show_profile_details()
    {
        $profile = Profile::factory()->create();

        $response = $this->getJson("/api/profiles/{$profile->id}");

        $response->assertStatus(200);
    }
}