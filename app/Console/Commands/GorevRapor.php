<?php

namespace App\Console\Commands;

use App\Models\Internship;
use App\Models\TaskSubmission;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan gorev:rapor komutuyla çalıştırılır.
// Seçilen stajlık ve isteğe bağlı görev filtresiyle tüm teslim kayıtlarını
// stajyer adı, görev başlığı, durum ve puan bilgisiyle tablo halinde listeler.
#[Signature('gorev:rapor')]
#[Description('Bir stajlığın görev teslim durumunu ve puanlarını listeler')]
class GorevRapor extends Command
{
    public function handle(): int
    {
        // Stajlık seçimi
        $stajliklar = Internship::with(['company', 'lessons.tasks'])->get();

        if ($stajliklar->isEmpty()) {
            $this->error('Sistemde kayıtlı stajlık bulunamadı.');

            return self::FAILURE;
        }

        $etiketler = $stajliklar->map(fn ($s) => $s->company->title.' / '.$s->title)->toArray();
        $secilenEtiket = $this->choice('Stajlık seçin', $etiketler);
        $stajlik = $stajliklar->firstWhere(fn ($s) => $s->company->title.' / '.$s->title === $secilenEtiket);

        // Stajlığa ait tüm görevler derlenir
        $tumGorevler = $stajlik->lessons->flatMap(fn ($ders) => $ders->tasks);

        if ($tumGorevler->isEmpty()) {
            $this->warn('Bu stajlığa ait görev bulunamadı.');

            return self::SUCCESS;
        }

        // Görev filtresi — tüm görevler veya belirli bir görev
        $gorevSecenekleri = array_merge(['Tüm Görevler'], $tumGorevler->pluck('title')->toArray());
        $secilenGorev = $this->choice('Görev filtresi', $gorevSecenekleri, 0);

        // TaskSubmission kayıtları eager load ile çekilir
        $sorgu = TaskSubmission::with(['intern_register.profile.user', 'task'])
            ->whereHas('task.lesson', fn ($q) => $q->where('internship_id', $stajlik->id));

        // Belirli görev seçildiyse filtre uygulanır
        if ($secilenGorev !== 'Tüm Görevler') {
            $gorev = $tumGorevler->firstWhere('title', $secilenGorev);
            $sorgu->where('task_id', $gorev->id);
        }

        $kayitlar = $sorgu->get();

        if ($kayitlar->isEmpty()) {
            $this->warn('Seçilen kriterlere göre teslim kaydı bulunamadı.');

            return self::SUCCESS;
        }

        // Tablo satırları oluşturulur
        $satirlar = $kayitlar->map(function ($teslim) {
            $stajyerAdi = optional($teslim->intern_register?->profile?->user)->name ?? 'Bilinmiyor';
            $gorevBasligi = optional($teslim->task)->title ?? 'Bilinmiyor';
            $durum = $teslim->status ?? 'Beklemede';
            $puan = $teslim->point !== null ? $teslim->point : '-';

            return [$stajyerAdi, $gorevBasligi, $durum, $puan];
        })->toArray();

        $this->table(['Stajyer', 'Görev', 'Durum', 'Puan'], $satirlar);
        $this->line("Toplam: <comment>{$kayitlar->count()}</comment> teslim kaydı");

        return self::SUCCESS;
    }
}
