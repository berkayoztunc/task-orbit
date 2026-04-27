<?php

use App\Models\Attendance;
use App\Models\Internship;
use App\Models\InternRegister;
use App\Models\Lesson;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

function webhookPayload(string $chatId, string $text): array
{
    return [
        'message' => [
            'chat' => ['id' => $chatId],
            'text' => $text,
        ],
    ];
}

beforeEach(function () {
    Http::fake();
    Role::create(['name' => 'Mentor']);
    Role::create(['name' => 'Intern']);
});

// --- Boş payload ---

it('returns ok when payload has no message', function () {
    $this->postJson('/api/webhook/telegram', [])
        ->assertSuccessful()
        ->assertJson(['ok' => true]);
});

// --- Kayıtsız kullanıcı (kayıt akışı) ---

it('sends error message when unregistered user sends unknown email', function () {
    $this->postJson('/api/webhook/telegram', webhookPayload('111', 'bilinmeyen@email.com'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains($req->data()['text'] ?? '', 'bulunamad'));
});

it('links telegram chat id when unregistered user sends valid email', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->postJson('/api/webhook/telegram', webhookPayload('999', 'test@example.com'))
        ->assertSuccessful();

    expect($user->fresh()->telegram_chat_id)->toBe('999');
    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'bağlandı'));
});

// --- Kayıtlı kullanıcı ---

it('sends error for unknown command from registered user', function () {
    $user = User::factory()->create(['telegram_chat_id' => '123']);

    $this->postJson('/api/webhook/telegram', webhookPayload('123', 'bilinmeyenkomut'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Geçersiz komut'));
});

// --- Yoklama komutu ---

it('rejects yoklama command from non-mentor user', function () {
    $user = User::factory()->create(['telegram_chat_id' => '123']);
    Profile::factory()->create(['user_id' => $user->id, 'role_id' => 2]);

    $this->postJson('/api/webhook/telegram', webhookPayload('123', 'yoklama'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'yalnızca mentörlere'));
});

it('sends error when mentor has no lessons', function () {
    $user = User::factory()->create(['telegram_chat_id' => '123']);
    Profile::factory()->create(['user_id' => $user->id, 'role_id' => 1]);

    $this->postJson('/api/webhook/telegram', webhookPayload('123', 'yoklama'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Atanmış dersiniz bulunamadı'));
});

it('lists lessons and caches them when mentor sends yoklama', function () {
    $user = User::factory()->create(['telegram_chat_id' => '123']);
    $profile = Profile::factory()->create(['user_id' => $user->id, 'role_id' => 1]);
    $lesson = Lesson::factory()->create(['profile_id' => $profile->id]);

    $this->postJson('/api/webhook/telegram', webhookPayload('123', 'yoklama'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains($req->data()['text'] ?? '', $lesson->title));
    expect(Cache::get("lesson_select:123"))->toBe([$lesson->id]);
});

// --- Ders numarası seçimi ---

it('sends attendance check to interns when mentor selects lesson number', function () {
    $mentor = User::factory()->create(['telegram_chat_id' => '123']);
    $mentorProfile = Profile::factory()->create(['user_id' => $mentor->id, 'role_id' => 1]);

    $intern = User::factory()->create(['telegram_chat_id' => '456']);
    $internProfile = Profile::factory()->create(['user_id' => $intern->id, 'role_id' => 2]);

    $internship = Internship::factory()->create();
    $lesson = Lesson::factory()->create(['profile_id' => $mentorProfile->id, 'internship_id' => $internship->id]);
    InternRegister::factory()->create(['profile_id' => $internProfile->id, 'internship_id' => $internship->id]);

    Cache::put("lesson_select:123", [$lesson->id], now()->addMinutes(5));

    $this->postJson('/api/webhook/telegram', webhookPayload('123', '1'))
        ->assertSuccessful();

    expect(Attendance::where('lesson_id', $lesson->id)->exists())->toBeTrue();
    Http::assertSent(fn ($req) => str_contains($req->data()['chat_id'] ?? '', '456'));
});

it('sends error for invalid lesson number', function () {
    $user = User::factory()->create(['telegram_chat_id' => '123']);
    $lesson = Lesson::factory()->create();

    Cache::put("lesson_select:123", [$lesson->id], now()->addMinutes(5));

    $this->postJson('/api/webhook/telegram', webhookPayload('123', '99'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Geçersiz numara'));
});

// --- Yoklama yanıtı ---

it('marks attendance as present when intern replies evet', function () {
    $user = User::factory()->create(['telegram_chat_id' => '456']);
    $internProfile = Profile::factory()->create(['user_id' => $user->id, 'role_id' => 2]);

    $internRegister = InternRegister::factory()->create(['profile_id' => $internProfile->id]);
    $lesson = Lesson::factory()->create();
    $attendance = Attendance::factory()->create([
        'intern_register_id' => $internRegister->id,
        'lesson_id' => $lesson->id,
        'status' => null,
    ]);

    $this->postJson('/api/webhook/telegram', webhookPayload('456', 'evet'))
        ->assertSuccessful();

    expect((bool) $attendance->fresh()->status)->toBeTrue();
    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Katılıyor'));
});

it('marks attendance as absent when intern replies hayır', function () {
    $user = User::factory()->create(['telegram_chat_id' => '456']);
    $internProfile = Profile::factory()->create(['user_id' => $user->id, 'role_id' => 2]);

    $internRegister = InternRegister::factory()->create(['profile_id' => $internProfile->id]);
    $lesson = Lesson::factory()->create();
    $attendance = Attendance::factory()->create([
        'intern_register_id' => $internRegister->id,
        'lesson_id' => $lesson->id,
        'status' => null,
    ]);

    $this->postJson('/api/webhook/telegram', webhookPayload('456', 'hayır'))
        ->assertSuccessful();

    expect((bool) $attendance->fresh()->status)->toBeFalse();
    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Katılmıyor'));
});

it('sends error when intern replies but has no pending attendance', function () {
    $user = User::factory()->create(['telegram_chat_id' => '456']);
    Profile::factory()->create(['user_id' => $user->id, 'role_id' => 2]);

    $this->postJson('/api/webhook/telegram', webhookPayload('456', 'evet'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => str_contains(($req->data()['text'] ?? ''), 'Bekleyen bir yoklama bulunamadı'));
});

it('notifies mentor when intern responds to attendance', function () {
    $mentor = User::factory()->create(['telegram_chat_id' => '123']);
    $mentorProfile = Profile::factory()->create(['user_id' => $mentor->id, 'role_id' => 1]);

    $intern = User::factory()->create(['telegram_chat_id' => '456']);
    $internProfile = Profile::factory()->create(['user_id' => $intern->id, 'role_id' => 2]);

    $internRegister = InternRegister::factory()->create(['profile_id' => $internProfile->id]);
    $lesson = Lesson::factory()->create(['profile_id' => $mentorProfile->id]);
    Attendance::factory()->create([
        'intern_register_id' => $internRegister->id,
        'lesson_id' => $lesson->id,
        'status' => null,
    ]);

    $this->postJson('/api/webhook/telegram', webhookPayload('456', 'evet'))
        ->assertSuccessful();

    Http::assertSent(fn ($req) => ($req->data()['chat_id'] ?? '') === '123' && str_contains($req->data()['text'] ?? '', 'Yoklama Güncellendi'));
});
