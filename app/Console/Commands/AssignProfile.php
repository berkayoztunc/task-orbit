<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan profile:assign komutuyla çalıştırılan Artisan komutudur
// Kullanıcıya rol ve şirket atayarak Profile tablosuna kayıt oluşturur
#[Signature('profile:assign')]
#[Description('Kullanıcıya profil atama komutu')]
class AssignProfile extends Command
{
    public function handle()
    {
        // Terminalde kullanıcıdan e-posta adresi istenir
        $email = $this->ask('Kullanıcının e-posta adresini girin');

        // Girilen e-posta ile veritabanında kullanıcı aranır
        $user = User::where('email', $email)->first();

        // Kullanıcı bulunamazsa hata mesajı gösterilip komut sonlandırılır
        if (! $user) {
            $this->error('Kullanıcı bulunamadı.');

            return;
        }

        // Kullanıcı bulunduysa adı terminale yazdırılır
        $this->info("Kullanıcı bulundu: {$user->name}");

        // Terminalde seçenek listesi gösterilerek rol seçtirilir
        $role = $this->choice('Rol seçin', ['Mentor', 'Intern']);

        // Seçilen rol ismine göre role_id belirlenir
        // Mentor → 1, Intern → 2 (roles tablosundaki id'ler)
        $roleId = $role === 'Mentor' ? 1 : 2;

        // Veritabanındaki tüm şirketler çekilir (id ve title alanları yeterli)
        $companies = Company::all(['id', 'title']);

        // Hiç şirket yoksa hata mesajı gösterilip komut sonlandırılır
        if ($companies->isEmpty()) {
            $this->error('Sistemde kayıtlı şirket bulunamadı.');

            return;
        }

        // Şirket isimlerinden oluşan dizi oluşturulur, choice() için gerekli
        $companyTitles = $companies->pluck('title')->toArray();

        // Terminalde şirket listesi gösterilerek seçim yaptırılır
        $selectedTitle = $this->choice('Şirket seçin', $companyTitles);

        // Seçilen isimle eşleşen şirket nesnesi koleksiyondan bulunur
        $company = $companies->firstWhere('title', $selectedTitle);

        // Profile tablosuna yeni kayıt oluşturulur
        // user_id → hangi kullanıcı, role_id → hangi rol, company_id → hangi şirket
        Profile::create([
            'user_id' => $user->id,
            'role_id' => $roleId,
            'company_id' => $company->id,
        ]);

        // İşlem tamamlandığında özet bilgi terminale yazdırılır
        $this->info("Profil oluşturuldu: {$user->name} → {$role} @ {$company->title}");
    }
}
