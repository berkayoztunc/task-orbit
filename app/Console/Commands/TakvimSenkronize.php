<?php

namespace App\Console\Commands;

use App\Jobs\SyncLessonToCalendar;
use App\Jobs\SyncTaskToCalendar;
use App\Models\Internship;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan takvim:senkronize komutuyla çalıştırılır.
// Seçilen stajlığa ait dersleri ve/veya görevleri Google Calendar ile
// senkronize etmek için SyncLessonToCalendar / SyncTaskToCalendar
// joblarını kuyruğa ekler. Observer'ı bypass etmez; mevcut job mimarisini kullanır.
#[Signature('takvim:senkronize')]
#[Description('Bir stajlığın derslerini ve/veya görevlerini Google Calendar ile senkronize eder')]
class TakvimSenkronize extends Command
{
    public function handle(): int
    {
        // Tüm stajlıklar listelenir; şirket adıyla birlikte gösterilir
        $stajliklar = Internship::with('company')->get();

        if ($stajliklar->isEmpty()) {
            $this->error('Sistemde kayıtlı stajlık bulunamadı.');

            return self::FAILURE;
        }

        // Stajlık seçimi — "Şirket / Stajlık" formatında gösterilir
        $etiketler = $stajliklar->map(fn ($s) => $s->company->title.' / '.$s->title)->toArray();
        $secilenEtiket = $this->choice('Stajlık seçin', $etiketler);
        $stajlik = $stajliklar->firstWhere(fn ($s) => $s->company->title.' / '.$s->title === $secilenEtiket);

        // Senkronizasyon türü seçimi
        $tur = $this->choice('Neyi senkronize etmek istiyorsunuz?', ['Dersler', 'Görevler', 'Hepsi'], 2);

        $dersler = $stajlik->lessons()->with('tasks')->get();

        if ($dersler->isEmpty()) {
            $this->warn('Bu stajlığa ait ders bulunamadı. Senkronize edilecek kayıt yok.');

            return self::SUCCESS;
        }

        $dersDispatch = 0;
        $gorevDispatch = 0;

        // Seçime göre dersler ve/veya görevler için joblar kuyruğa eklenir
        $this->withProgressBar($dersler, function ($ders) use ($tur, &$dersDispatch, &$gorevDispatch) {
            if (in_array($tur, ['Dersler', 'Hepsi'])) {
                SyncLessonToCalendar::dispatch($ders);
                $dersDispatch++;
            }

            if (in_array($tur, ['Görevler', 'Hepsi'])) {
                foreach ($ders->tasks as $gorev) {
                    SyncTaskToCalendar::dispatch($gorev);
                    $gorevDispatch++;
                }
            }
        });

        $this->newLine(2);
        $this->info("✅ Kuyruğa eklendi: {$dersDispatch} ders, {$gorevDispatch} görev job'u dispatch edildi.");
        $this->line('Jobları çalıştırmak için: <comment>php artisan queue:work</comment>');

        return self::SUCCESS;
    }
}
