<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Internship;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan stajlik:durum komutuyla çalıştırılır.
// Şirket → stajlık kademeli seçiminden sonra stajlığın durumunu
// Aktif veya Pasif olarak günceller.
#[Signature('stajlik:durum')]
#[Description('Bir stajlığın durumunu (aktif/pasif) günceller')]
class StajlikDurum extends Command
{
    public function handle(): int
    {
        // Önce şirket seçimi yapılır
        $sirketler = Company::all(['id', 'title']);

        if ($sirketler->isEmpty()) {
            $this->error('Sistemde kayıtlı şirket bulunamadı.');

            return self::FAILURE;
        }

        $secilenSirketBasligi = $this->choice('Şirket seçin', $sirketler->pluck('title')->toArray());
        $sirket = $sirketler->firstWhere('title', $secilenSirketBasligi);

        // Seçilen şirkete ait stajlıklar listelenir
        $stajliklar = Internship::where('company_id', $sirket->id)->get(['id', 'title', 'status']);

        if ($stajliklar->isEmpty()) {
            $this->error("{$sirket->title} şirketine ait stajlık bulunamadı.");

            return self::FAILURE;
        }

        // Stajlık isimlerinden seçim yaptırılır
        $secilenStajlikBasligi = $this->choice('Stajlık seçin', $stajliklar->pluck('title')->toArray());
        $stajlik = $stajliklar->firstWhere('title', $secilenStajlikBasligi);

        // Mevcut durum gösterilir
        $mevcutDurum = $stajlik->status === 'active' ? 'Aktif' : 'Pasif';
        $this->line("Mevcut durum: <comment>{$mevcutDurum}</comment>");

        // Yeni durum seçimi; mevcut durum varsayılan olarak işaretlenir
        $varsayilan = $stajlik->status === 'active' ? 0 : 1;
        $yeniDurum = $this->choice('Yeni durum seçin', ['Aktif', 'Pasif'], $varsayilan);
        $yeniDurumDegeri = $yeniDurum === 'Aktif' ? 'active' : 'inactive';

        if ($yeniDurumDegeri === $stajlik->status) {
            $this->warn('Durum değiştirilmedi — seçilen değer mevcut değerle aynı.');

            return self::SUCCESS;
        }

        // Güncelleme yapılır; Internship observer yok, direkt update güvenli
        $stajlik->update(['status' => $yeniDurumDegeri]);

        $this->info("✅ Durum güncellendi: {$stajlik->title} → {$yeniDurum}");

        return self::SUCCESS;
    }
}
