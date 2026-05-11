<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Internship;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan devamsizlik:rapor komutuyla çalıştırılır.
// Seçilen stajlık ve isteğe bağlı ders filtresiyle devamsızlık kayıtlarını
// tablo halinde listeler. Stajyer adı, ders, tarih ve durum gösterilir.
#[Signature('devamsizlik:rapor')]
#[Description('Bir stajlığın devamsızlık kayıtlarını listeler')]
class DevamsizlikRapor extends Command
{
    public function handle(): int
    {
        // Stajlık seçimi
        $stajliklar = Internship::with(['company', 'lessons'])->get();

        if ($stajliklar->isEmpty()) {
            $this->error('Sistemde kayıtlı stajlık bulunamadı.');

            return self::FAILURE;
        }

        $etiketler = $stajliklar->map(fn ($s) => $s->company->title.' / '.$s->title)->toArray();
        $secilenEtiket = $this->choice('Stajlık seçin', $etiketler);
        $stajlik = $stajliklar->firstWhere(fn ($s) => $s->company->title.' / '.$s->title === $secilenEtiket);

        // Ders filtresi — "Tüm Dersler" veya belirli bir ders seçilebilir
        $dersSecenekleri = array_merge(['Tüm Dersler'], $stajlik->lessons->pluck('title')->toArray());
        $secilenDers = $this->choice('Ders filtresi', $dersSecenekleri, 0);

        // Devamsızlık kayıtları eager load ile çekilir
        $sorgu = Attendance::with(['intern_register.profile.user', 'lesson'])
            ->whereHas('lesson', fn ($q) => $q->where('internship_id', $stajlik->id));

        // Belirli ders seçildiyse filtre uygulanır
        if ($secilenDers !== 'Tüm Dersler') {
            $ders = $stajlik->lessons->firstWhere('title', $secilenDers);
            $sorgu->where('lesson_id', $ders->id);
        }

        $kayitlar = $sorgu->orderBy('date')->get();

        if ($kayitlar->isEmpty()) {
            $this->warn('Seçilen kriterlere göre devamsızlık kaydı bulunamadı.');

            return self::SUCCESS;
        }

        // Tablo satırları oluşturulur; status: true = Katıldı, false = Katılmadı
        $satirlar = $kayitlar->map(function ($kayit) {
            $stajyerAdi = optional($kayit->intern_register?->profile?->user)->name ?? 'Bilinmiyor';
            $dersAdi = optional($kayit->lesson)->title ?? 'Bilinmiyor';
            $tarih = $kayit->date ? $kayit->date->format('d.m.Y') : '-';
            $durum = $kayit->status ? '✅ Katıldı' : '❌ Katılmadı';

            return [$stajyerAdi, $dersAdi, $tarih, $durum];
        })->toArray();

        $this->table(['Stajyer', 'Ders', 'Tarih', 'Durum'], $satirlar);
        $this->line("Toplam: <comment>{$kayitlar->count()}</comment> kayıt");

        return self::SUCCESS;
    }
}
