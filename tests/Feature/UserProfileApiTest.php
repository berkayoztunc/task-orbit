<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_switch_profile()
    {
        $user = User::factory()->create();
        $company = Company::create(['title' => 'Test Şirketi']);
        $role = Role::create(['name' => 'Stajyer']);

        $profile = Profile::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role_id' => $role->id,
        ]);

        $response = $this->patchJson("/api/users/{$user->id}/switch-profile", [
            'profile_id' => $profile->id
        ]);

        $response->assertStatus(200);
    }
}