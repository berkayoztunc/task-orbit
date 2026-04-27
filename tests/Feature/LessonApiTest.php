<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Dersleri listeleme testi.
     */
    public function test_it_can_list_lessons()
    {
        Lesson::factory()->count(3)->create();

        $response = $this->getJson('/api/lessons');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');
    }

    public function test_it_can_create_a_lesson()
    {
        $company = \App\Models\Company::factory()->create();
        $internship = \App\Models\Internship::factory()->create();
        $profile = \App\Models\Profile::factory()->create();

        $payload = [
            'company_id'    => $company->id,
            'internship_id' => $internship->id,
            'profile_id'    => $profile->id,
            'title'         => 'Laravel Testing Eğitimi',
            'description'   => 'Bu derste Feature testlerinin nasıl yazılacağı anlatılmaktadır.',
            'content'       => 'Ders içeriği detayları burada yer alıyor.',
            'start_date'    => '2026-05-01',
            'end_date'      => '2026-05-02',
            'status'        => 1
        ];

        $response = $this->postJson('/api/lessons', $payload);

        
        $response->assertStatus(200) 
                 ->assertJsonPath('data.title', 'Laravel Testing Eğitimi');

        $this->assertDatabaseHas('lessons', [
            'title' => 'Laravel Testing Eğitimi'
        ]);
    }
    /**
     * Tek bir dersin detaylarını görme testi.
     */
    public function test_it_can_show_lesson_details()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->getJson("/api/lessons/{$lesson->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $lesson->id);
    }
}