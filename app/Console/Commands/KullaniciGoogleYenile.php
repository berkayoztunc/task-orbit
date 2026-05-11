<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan kullanici:google-yenile komutuyla çalıştırılır.
// google_refresh_token değeri olan tüm kullanıcıları listeler ve
// GoogleCalendarService constructor'ını kullanarak access token'larını yeniler.
// Servis zaten token yenileme mantığını içerdiğinden ayrı bir işlem gerekmez.
#[Signature('kullanici:google-yenile')]
#[Description('Google OAuth token süresi dolan kullanıcıların access token\'larını yeniler')]
class KullaniciGoogleYenile extends Command
{
    public function handle(): int
    {
        // google_refresh_token olan kullanıcılar; bunlar token yenilenebilir kullanıcılardır
        $kullanicilar = User::whereNotNull('google_refresh_token')->get();

        if ($kullanicilar->isEmpty()) {
            $this->warn('Google refresh token tanımlı kullanıcı bulunamadı.');
            $this->line('Kullanıcıların Google Calendar bağlaması için OAuth akışını tamamlamaları gerekir.');

            return self::SUCCESS;
        }

        $this->info("{$kullanicilar->count()} kullanıcı Google refresh token'ına sahip:");
        $this->table(
            ['Ad', 'E-posta', 'Access Token Var mı?'],
            $kullanicilar->map(fn ($u) => [$u->name, $u->email, $u->google_token ? 'Evet' : 'Hayır'])->toArray()
        );
        $this->newLine();

        // İşlem onayı istenir; geri alınamaz değil ama istemeden çalışmasın
        if (! $this->confirm('Yukarıdaki kullanıcıların Google token\'ları yenilensin mi?')) {
            $this->line('İptal edildi.');

            return self::SUCCESS;
        }

        $basarili = 0;
        $basarisiz = 0;

        // Her kullanıcı için GoogleCalendarService örneklenir.
        // Constructor token süresi dolmuşsa refresh_token ile yeniler ve DB'ye kaydeder.
        $this->withProgressBar($kullanicilar, function ($kullanici) use (&$basarili, &$basarisiz) {
            try {
                new GoogleCalendarService($kullanici);
                $basarili++;
            } catch (\Throwable $e) {
                $basarisiz++;
                $this->newLine();
                $this->warn("{$kullanici->email}: ".$e->getMessage());
            }
        });

        $this->newLine(2);
        $this->info("✅ Tamamlandı — Başarılı: {$basarili} | Başarısız: {$basarisiz}");

        return $basarisiz > 0 ? self::FAILURE : self::SUCCESS;
    }
}
