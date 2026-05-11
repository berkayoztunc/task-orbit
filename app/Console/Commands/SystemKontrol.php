<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// php artisan system:kontrol komutuyla çalıştırılır.
// Uygulamanın kritik servislerini (veritabanı, önbellek, kuyruk,
// Telegram, Google Calendar, APP_KEY) sırayla kontrol eder ve
// sonuçları tablo halinde ekrana basar. Sorun varsa exit kodu 1 döner.
#[Signature('system:kontrol')]
#[Description('Sistem servislerini kontrol eder (DB, cache, kuyruk, Telegram, Google, APP_KEY)')]
class SystemKontrol extends Command
{
    public function handle(): int
    {
        $this->info('Task Orbit — Sistem Kontrol Raporu');
        $this->newLine();

        $satirlar = [];
        $hata = false;

        // --- Veritabanı bağlantısı ---
        try {
            DB::select('SELECT 1');
            $satirlar[] = ['🗄️  Veritabanı', '✅ Bağlantı başarılı'];
        } catch (\Throwable $e) {
            $satirlar[] = ['🗄️  Veritabanı', '❌ '.$e->getMessage()];
            $hata = true;
        }

        // --- Önbellek (cache) okuma/yazma ---
        try {
            Cache::put('system_kontrol_test', 'ok', 5);
            $deger = Cache::get('system_kontrol_test');
            Cache::forget('system_kontrol_test');

            if ($deger === 'ok') {
                $satirlar[] = ['⚡ Önbellek', '✅ Okuma/yazma başarılı'];
            } else {
                $satirlar[] = ['⚡ Önbellek', '❌ Değer okunamadı'];
                $hata = true;
            }
        } catch (\Throwable $e) {
            $satirlar[] = ['⚡ Önbellek', '❌ '.$e->getMessage()];
            $hata = true;
        }

        // --- Kuyruk: jobs ve failed_jobs tablosu ---
        try {
            $bekleyen = DB::table('jobs')->count();
            $basarisiz = DB::table('failed_jobs')->count();
            $satirlar[] = ['📨 Kuyruk', "✅ Bekleyen: {$bekleyen} | Başarısız: {$basarisiz}"];
        } catch (\Throwable $e) {
            $satirlar[] = ['📨 Kuyruk', '❌ '.$e->getMessage()];
            $hata = true;
        }

        // --- Telegram yapılandırması (.env TELEGRAM_TOKEN, TELEGRAM_CHAT_ID) ---
        $telegramToken = config('services.telegram.token');
        $telegramChatId = config('services.telegram.chat_id');

        if ($telegramToken && $telegramChatId) {
            $satirlar[] = ['💬 Telegram', '✅ Token ve Chat ID tanımlı'];
        } else {
            $eksik = collect(['token' => $telegramToken, 'chat_id' => $telegramChatId])
                ->filter(fn ($v) => ! $v)->keys()->implode(', ');
            $satirlar[] = ['💬 Telegram', "⚠️  Eksik: {$eksik}"];
        }

        // --- Google Calendar yapılandırması (.env GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET) ---
        $googleClientId = config('services.google.client_id');
        $googleClientSecret = config('services.google.client_secret');

        if ($googleClientId && $googleClientSecret) {
            $satirlar[] = ['📅 Google Calendar', '✅ Client ID ve Secret tanımlı'];
        } else {
            $eksik = collect(['client_id' => $googleClientId, 'client_secret' => $googleClientSecret])
                ->filter(fn ($v) => ! $v)->keys()->implode(', ');
            $satirlar[] = ['📅 Google Calendar', "⚠️  Eksik: {$eksik}"];
        }

        // --- APP_KEY varlığı kontrolü ---
        $appKey = config('app.key');

        if ($appKey) {
            $satirlar[] = ['🔑 APP_KEY', '✅ Tanımlı'];
        } else {
            $satirlar[] = ['🔑 APP_KEY', '❌ Tanımlı değil — php artisan key:generate çalıştırın'];
            $hata = true;
        }

        // --- Uygulama ortamı ve debug modu ---
        $env = config('app.env');
        $debug = config('app.debug') ? 'true ⚠️' : 'false ✅';
        $satirlar[] = ['🌍 Ortam', "{$env} | DEBUG={$debug}"];

        $this->table(['Servis', 'Durum'], $satirlar);

        if ($hata) {
            $this->newLine();
            $this->error('Kritik sorun tespit edildi. Lütfen yukarıdaki hataları giderin.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Tüm servisler normal çalışıyor.');

        return self::SUCCESS;
    }
}
