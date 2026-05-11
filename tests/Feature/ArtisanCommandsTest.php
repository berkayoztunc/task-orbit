<?php

use App\Jobs\SendTelegramMessage;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\InternRegister;
use App\Models\Internship;
use App\Models\Lesson;
use App\Models\Profile;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

// ─────────────────────────────────────────────────────────────
// system:kontrol
// ─────────────────────────────────────────────────────────────

it('system:kontrol runs successfully and outputs health table', function () {
    $this->artisan('system:kontrol')
        ->expectsOutputToContain('Veritabanı')
        ->expectsOutputToContain('Önbellek')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// veritabani:istatistik
// ─────────────────────────────────────────────────────────────

it('veritabani:istatistik shows record counts', function () {
    Company::factory()->count(2)->create();

    $this->artisan('veritabani:istatistik')
        ->expectsOutputToContain('Şirketler')
        ->expectsOutputToContain('Kullanıcılar')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// stajlik:olustur
// ─────────────────────────────────────────────────────────────

it('stajlik:olustur creates an internship via prompts', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);

    $this->artisan('stajlik:olustur')
        ->expectsChoice('Şirket seçin', 'Test Şirket', ['Test Şirket'])
        ->expectsQuestion('Stajlık başlığını girin', 'Yaz Stajı 2026')
        ->expectsQuestion('Açıklama girin (boş bırakılabilir)', '')
        ->expectsChoice('Durum seçin', 'Aktif', ['Aktif', 'Pasif'])
        ->assertSuccessful();

    expect(Internship::where('title', 'Yaz Stajı 2026')->exists())->toBeTrue();
});

it('stajlik:olustur fails when title is empty', function () {
    Company::factory()->create(['title' => 'Test Şirket']);

    $this->artisan('stajlik:olustur')
        ->expectsChoice('Şirket seçin', 'Test Şirket', ['Test Şirket'])
        ->expectsQuestion('Stajlık başlığını girin', '')
        ->assertFailed();
});

it('stajlik:olustur fails when no company exists', function () {
    $this->artisan('stajlik:olustur')
        ->expectsOutputToContain('Sistemde kayıtlı şirket bulunamadı')
        ->assertFailed();
});

// ─────────────────────────────────────────────────────────────
// stajlik:durum
// ─────────────────────────────────────────────────────────────

it('stajlik:durum updates internship status from active to inactive', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $stajlik = Internship::factory()->create([
        'company_id' => $sirket->id,
        'title' => 'Test Stajlık',
        'status' => 'active',
    ]);

    $this->artisan('stajlik:durum')
        ->expectsChoice('Şirket seçin', 'Test Şirket', ['Test Şirket'])
        ->expectsChoice('Stajlık seçin', 'Test Stajlık', ['Test Stajlık'])
        ->expectsChoice('Yeni durum seçin', 'Pasif', ['Aktif', 'Pasif'])
        ->assertSuccessful();

    expect($stajlik->fresh()->status)->toBe('inactive');
});

it('stajlik:durum warns when same status selected', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    Internship::factory()->create([
        'company_id' => $sirket->id,
        'title' => 'Test Stajlık',
        'status' => 'active',
    ]);

    $this->artisan('stajlik:durum')
        ->expectsChoice('Şirket seçin', 'Test Şirket', ['Test Şirket'])
        ->expectsChoice('Stajlık seçin', 'Test Stajlık', ['Test Stajlık'])
        ->expectsChoice('Yeni durum seçin', 'Aktif', ['Aktif', 'Pasif'])
        ->expectsOutputToContain('Durum değiştirilmedi')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// takvim:senkronize
// ─────────────────────────────────────────────────────────────

it('takvim:senkronize warns when no lessons exist for internship', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Boş Stajlık']);

    $this->artisan('takvim:senkronize')
        ->expectsChoice('Stajlık seçin', 'Test Şirket / Boş Stajlık', ['Test Şirket / Boş Stajlık'])
        ->expectsChoice('Neyi senkronize etmek istiyorsunuz?', 'Hepsi', ['Dersler', 'Görevler', 'Hepsi'])
        ->expectsOutputToContain('ders bulunamadı')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// devamsizlik:rapor
// ─────────────────────────────────────────────────────────────

it('devamsizlik:rapor shows attendance records', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $stajlik = Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Stajlık']);
    $ders = Lesson::factory()->create(['internship_id' => $stajlik->id]);
    $internRegister = InternRegister::factory()->create(['internship_id' => $stajlik->id]);
    Attendance::factory()->create(['lesson_id' => $ders->id, 'intern_register_id' => $internRegister->id]);

    $this->artisan('devamsizlik:rapor')
        ->expectsChoice('Stajlık seçin', 'Test Şirket / Stajlık', ['Test Şirket / Stajlık'])
        ->expectsChoice('Ders filtresi', 'Tüm Dersler', array_merge(['Tüm Dersler'], [$ders->title]))
        ->assertSuccessful();
});

it('devamsizlik:rapor warns when no attendance records exist', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $stajlik = Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Stajlık']);
    $ders = Lesson::factory()->create(['internship_id' => $stajlik->id]);

    $this->artisan('devamsizlik:rapor')
        ->expectsChoice('Stajlık seçin', 'Test Şirket / Stajlık', ['Test Şirket / Stajlık'])
        ->expectsChoice('Ders filtresi', 'Tüm Dersler', array_merge(['Tüm Dersler'], [$ders->title]))
        ->expectsOutputToContain('kaydı bulunamadı')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// telegram:test
// ─────────────────────────────────────────────────────────────

it('telegram:test fails when token config is missing', function () {
    config(['services.telegram.token' => null]);

    $this->artisan('telegram:test')
        ->expectsOutputToContain('Telegram yapılandırması eksik')
        ->assertFailed();
});

it('telegram:test dispatches job when config is present', function () {
    Queue::fake();

    config(['services.telegram.token' => 'fake-token-xyz', 'services.telegram.chat_id' => '99999']);

    $this->artisan('telegram:test')
        ->expectsQuestion('Gönderilecek mesajı girin', 'Test mesajı')
        ->assertSuccessful();

    Queue::assertPushed(SendTelegramMessage::class);
});

// ─────────────────────────────────────────────────────────────
// gorev:rapor
// ─────────────────────────────────────────────────────────────

it('gorev:rapor shows task submission records', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $stajlik = Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Stajlık']);
    $ders = Lesson::factory()->create(['internship_id' => $stajlik->id]);
    $gorev = Task::factory()->create(['lesson_id' => $ders->id]);
    $internRegister = InternRegister::factory()->create(['internship_id' => $stajlik->id]);
    TaskSubmission::factory()->create(['task_id' => $gorev->id, 'intern_register_id' => $internRegister->id]);

    $this->artisan('gorev:rapor')
        ->expectsChoice('Stajlık seçin', 'Test Şirket / Stajlık', ['Test Şirket / Stajlık'])
        ->expectsChoice('Görev filtresi', 'Tüm Görevler', array_merge(['Tüm Görevler'], [$gorev->title]))
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// kullanici:google-yenile
// ─────────────────────────────────────────────────────────────

it('kullanici:google-yenile warns when no users have refresh token', function () {
    User::factory()->create(['google_refresh_token' => null]);

    $this->artisan('kullanici:google-yenile')
        ->expectsOutputToContain('Google refresh token tanımlı kullanıcı bulunamadı')
        ->assertSuccessful();
});

it('kullanici:google-yenile cancels when user declines', function () {
    User::factory()->create(['google_refresh_token' => 'refresh-abc']);

    $this->artisan('kullanici:google-yenile')
        ->expectsConfirmation('Yukarıdaki kullanıcıların Google token\'ları yenilensin mi?', 'no')
        ->expectsOutputToContain('İptal edildi')
        ->assertSuccessful();
});

// ─────────────────────────────────────────────────────────────
// stajyer:kayit
// ─────────────────────────────────────────────────────────────

it('stajyer:kayit fails for unknown email', function () {
    $this->artisan('stajyer:kayit')
        ->expectsQuestion('Stajyerin e-posta adresini girin', 'yok@yok.com')
        ->expectsOutputToContain('kullanıcı bulunamadı')
        ->assertFailed();
});

it('stajyer:kayit fails when user has no profile', function () {
    User::factory()->create(['email' => 'stajyer@test.com']);

    $this->artisan('stajyer:kayit')
        ->expectsQuestion('Stajyerin e-posta adresini girin', 'stajyer@test.com')
        ->expectsOutputToContain('profil bulunamadı')
        ->assertFailed();
});

it('stajyer:kayit creates intern register successfully', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $kullanici = User::factory()->create(['email' => 'stajyer@test.com']);
    $profil = Profile::factory()->create(['user_id' => $kullanici->id, 'company_id' => $sirket->id]);
    $stajlik = Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Yaz Stajı']);

    $this->artisan('stajyer:kayit')
        ->expectsQuestion('Stajyerin e-posta adresini girin', 'stajyer@test.com')
        ->expectsChoice('Stajlık seçin', 'Yaz Stajı', ['Yaz Stajı'])
        ->assertSuccessful();

    expect(InternRegister::where('profile_id', $profil->id)->where('internship_id', $stajlik->id)->exists())->toBeTrue();
});

it('stajyer:kayit warns on duplicate registration', function () {
    $sirket = Company::factory()->create(['title' => 'Test Şirket']);
    $kullanici = User::factory()->create(['email' => 'stajyer@test.com']);
    $profil = Profile::factory()->create(['user_id' => $kullanici->id, 'company_id' => $sirket->id]);
    $stajlik = Internship::factory()->create(['company_id' => $sirket->id, 'title' => 'Yaz Stajı']);
    InternRegister::factory()->create(['profile_id' => $profil->id, 'internship_id' => $stajlik->id]);

    $this->artisan('stajyer:kayit')
        ->expectsQuestion('Stajyerin e-posta adresini girin', 'stajyer@test.com')
        ->expectsChoice('Stajlık seçin', 'Yaz Stajı', ['Yaz Stajı'])
        ->expectsOutputToContain('zaten')
        ->assertSuccessful();
});
