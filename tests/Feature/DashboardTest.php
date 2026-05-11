<?php

use App\Models\Company;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users without a profile are redirected to profile select', function () {
    $user = User::factory()->create(['current_profile_id' => 0]);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('page.profile.select'));
});

test('authenticated users with an active profile can visit the dashboard', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $role = Role::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'role_id' => $role->id,
    ]);
    $user->update(['current_profile_id' => $profile->id]);
    $this->actingAs($user->fresh());

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});
