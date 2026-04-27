<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\InternRegister;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_attendances()
    {
        // Önce sahte veriler oluşturalım
        Attendance::factory()->count(5)->create();

        $response = $this->getJson('/api/attendances');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');
    }

    public function test_it_can_record_a_new_attendance()
    {
        $register = InternRegister::factory()->create();
        $lesson = Lesson::factory()->create();

        $payload = [
            'intern_register_id' => $register->id,
            'lesson_id' => $lesson->id,
            'status' => 1 
        ];

        $response = $this->postJson('/api/attendances', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.status', 1);

        $this->assertDatabaseHas('attendances', [
            'intern_register_id' => $register->id,
            'lesson_id' => $lesson->id,
            'status' => 1
        ]);
    }

    public function test_it_can_update_attendance_status()
    {
        $attendance = Attendance::factory()->create(['status' => 1]);

        
        $response = $this->putJson("/api/attendances/{$attendance->id}", [
            'status' => 0
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => 0
        ]);
    }
}