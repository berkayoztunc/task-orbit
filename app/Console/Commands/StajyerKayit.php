<?php

namespace App\Console\Commands;

use App\Models\InternRegister;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan stajyer:kayit komutuyla çalıştırılır.
// E-posta ile kullanıcıyı bulur, kullanıcının profiline bağlı şirketteki
// stajlıklardan birini seçtirerek InternRegister kaydı oluşturur.
// Mükerrer kayıt kontrolü yapılır.
#[Signature('stajyer:kayit')]
#[Description('Bir stajyeri stajlığa kaydeder (InternRegister oluşturur)')]
class StajyerKayit extends Command
{
    public function handle(): int
    {
        // Kullanıcı e-posta ile aranır
        $email = $this->ask('Stajyerin e-posta adresini girin');
        $kullanici = User::where('email', $email)->first();

        if (! $kullanici) {
            $this->error("'{$email}' adresine sahip kullanıcı bulunamadı.");

            return self::FAILURE;
        }

        $this->info("Kullanıcı bulundu: {$kullanici->name}");

        // Kullanıcının profilinden şirket bilgisi alınır
        $profil = $kullanici->profiles()->with('company')->first();

        if (! $profil) {
            $this->error('Bu kullanıcıya atanmış profil bulunamadı. Önce profile:assign çalıştırın.');

            return self::FAILURE;
        }

        // Kullanıcının şirketine ait stajlıklar listelenir
        $stajliklar = Internship::where('company_id', $profil->company_id)->get(['id', 'title', 'status']);

        if ($stajliklar->isEmpty()) {
            $this->error("{$profil->company->title} şirketine ait stajlık bulunamadı.");

            return self::FAILURE;
        }

        $secilenBaslik = $this->choice('Stajlık seçin', $stajliklar->pluck('title')->toArray());
        $stajlik = $stajliklar->firstWhere('title', $secilenBaslik);

        // Mükerrer kayıt kontrolü: aynı profil + stajlık kombinasyonu var mı?
        $mevcutKayit = InternRegister::where('profile_id', $profil->id)
            ->where('internship_id', $stajlik->id)
            ->exists();

        if ($mevcutKayit) {
            $this->warn("{$kullanici->name} zaten '{$stajlik->title}' stajlığına kayıtlı.");

            return self::SUCCESS;
        }

        // InternRegister kaydı oluşturulur
        InternRegister::create([
            'profile_id' => $profil->id,
            'internship_id' => $stajlik->id,
            'status' => 'pending',
            'message' => '',
        ]);

        $this->info("✅ Kayıt tamamlandı: {$kullanici->name} → {$stajlik->title} (Durum: Beklemede)");

        return self::SUCCESS;
    }
}
