<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\InternRegister;
use App\Models\Internship;
use App\Models\Lesson;
use App\Models\Profile;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// php artisan veritabani:istatistik komutuyla çalıştırılır.
// Tüm ana tablolardaki kayıt sayılarını ve failed_jobs tablosunu
// tek bakışta görmek için tablo halinde basar. Argüman gerekmez.
#[Signature('veritabani:istatistik')]
#[Description('Tüm ana tablolardaki kayıt sayılarını tablo halinde listeler')]
class VeritabaniIstatistik extends Command
{
    public function handle(): int
    {
        $this->info('Task Orbit — Veritabanı İstatistikleri');
        $this->newLine();

        // Her model için kayıt sayısı sorgulanır
        $satirlar = [
            ['👥 Kullanıcılar', User::count()],
            ['🏢 Şirketler', Company::count()],
            ['🪪  Profiller', Profile::count()],
            ['🎓 Stajlıklar', Internship::count()],
            ['📋 Stajyer Kayıtları', InternRegister::count()],
            ['📚 Dersler', Lesson::count()],
            ['✏️  Görevler', Task::count()],
            ['📨 Teslimler', TaskSubmission::count()],
            ['📅 Devamsızlık Kayıtları', Attendance::count()],
        ];

        // Kuyruk tablolarının durumu da eklenir
        try {
            $bekleyen = DB::table('jobs')->count();
            $basarisiz = DB::table('failed_jobs')->count();
            $satirlar[] = ['📨 Bekleyen Job', $bekleyen];
            $satirlar[] = ['❌ Başarısız Job', $basarisiz];
        } catch (\Throwable) {
            // Jobs tablosu yoksa sessizce geç
        }

        $this->table(['Tablo', 'Kayıt Sayısı'], $satirlar);

        // Toplam kayıt sayısı hesaplanır (job tabloları hariç)
        $toplamKayit = array_sum(array_column(array_slice($satirlar, 0, 9), 1));
        $this->line("Toplam uygulama kaydı: <comment>{$toplamKayit}</comment>");

        return self::SUCCESS;
    }
}
