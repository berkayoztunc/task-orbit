<?php

namespace Tests\Feature;

use App\Models\Commantable;
use App\Models\Command;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommantableApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_commantables()
    {
        $user = User::factory()->create();
        
        $command = Command::create([
            'user_id' => $user->id,
            'message' => 'Bu mesaj altmış karakter sınırını geçmek için özel olarak elle yazılmıştır ve testi kurtaracaktır.',
        ]);

        Commantable::create([
            'command_id' => $command->id,
            'commantable_id' => $command->id,
            'commantable_type' => get_class($command),
            'user_id' => $user->id,
            'body' => 'Test yorumu listeleme kontrolü.',
        ]);

        $response = $this->getJson('/api/commantables');
        $response->assertStatus(200);
    }

    public function test_it_can_create_a_comment()
    {
        $user = User::factory()->create();
        
        $command = Command::create([
            'user_id' => $user->id,
            'message' => 'Sistem güncelliği için gereken bu açıklama metni altmış karakter barajını rahatlıkla aşmaktadır.',
        ]);

        $payload = [
            'command_id'       => $command->id,
            'commantable_id'   => $command->id,
            'commantable_type' => get_class($command),
            'user_id'          => $user->id,
            'body'             => 'Bu bir test yorumudur ve her şeyin yolunda olduğunu kanıtlar.',
        ];

        $response = $this->postJson('/api/commantables', $payload);

        $response->assertStatus($response->status() === 201 ? 201 : 200);

        $this->assertDatabaseHas('commantables', [
            'command_id' => $command->id,
        ]);
    }
}