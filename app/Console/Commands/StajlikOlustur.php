<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Internship;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan stajlik:olustur komutuyla çalıştırılır.
// Mevcut şirketlerden birini seçtirerek yeni bir stajlık kaydı oluşturur.
// Başlık, açıklama ve durum interaktif olarak alınır.
#[Signature('stajlik:olustur')]
#[Description('Yeni bir stajlık (internship) kaydı oluşturur')]
class StajlikOlustur extends Command
{
    public function handle(): int
    {
        // Veritabanındaki tüm şirketler çekilir
        $sirketler = Company::all(['id', 'title']);

        if ($sirketler->isEmpty()) {
            $this->error('Sistemde kayıtlı şirket bulunamadı. Önce bir şirket ekleyin.');

            return self::FAILURE;
        }

        // Kullanıcıdan şirket seçimi istenir
        $sirketBasliklari = $sirketler->pluck('title')->toArray();
        $secilenBaslik = $this->choice('Şirket seçin', $sirketBasliklari);
        $sirket = $sirketler->firstWhere('title', $secilenBaslik);

        // Stajlık başlığı alınır; boş bırakılamaz
        $baslik = $this->ask('Stajlık başlığını girin');

        if (! $baslik) {
            $this->error('Başlık boş bırakılamaz.');

            return self::FAILURE;
        }

        // Açıklama opsiyoneldir; boş bırakılabilir
        $aciklama = $this->ask('Açıklama girin (boş bırakılabilir)');

        // Stajlık durumu seçilir
        $durum = $this->choice('Durum seçin', ['Aktif', 'Pasif'], 0);
        $durumDegeri = $durum === 'Aktif' ? 'active' : 'inactive';

        // Internship kaydı oluşturulur; LessonObserver tetiklenmez (henüz ders yok)
        $stajlik = Internship::create([
            'company_id' => $sirket->id,
            'title' => $baslik,
            'description' => $aciklama ?: '',
            'status' => $durumDegeri,
        ]);

        $this->info("✅ Stajlık oluşturuldu: [{$stajlik->id}] {$stajlik->title} @ {$sirket->title} ({$durum})");

        return self::SUCCESS;
    }
}
