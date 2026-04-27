<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_are_accessible()
    {
        
        $response = $this->getJson('/api/admin/profiles');
        
        $this->assertNotEquals(500, $response->status());
    }
}