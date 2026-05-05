<?php

namespace Tests\Feature;

use App\Models\Command;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_commands()
    {
        $user = User::factory()->create();
        
        Command::create([
            'user_id' => $user->id,
            'message' => 'Test mesajı'
        ]);

        $response = $this->getJson('/api/commands');

        $response->assertStatus(200);
    }

   public function test_it_can_create_a_command()
    {
        $user = \App\Models\User::factory()->create();

        $longMessage = "Sistemi acil olarak en güncel versiyona taşımamız gerekiyor, lütfen tüm veritabanı yedeğini alıp işlemleri hemen başlatın.";

        $payload = [
            'user_id' => $user->id,
            'message' => $longMessage,
        ];

        $response = $this->postJson('/api/commands', $payload);

        $response->assertStatus($response->status() === 201 ? 201 : 200);

        $this->assertDatabaseHas('commands', [
            'user_id' => $user->id,
            'message' => $longMessage
        ]);
    }
}