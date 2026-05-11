<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramMessage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// php artisan telegram:test komutuyla çalıştırılır.
// .env'deki TELEGRAM_TOKEN ve TELEGRAM_CHAT_ID değerlerini doğrular,
// ardından kullanıcının girdiği mesajı SendTelegramMessage job'u ile kuyruğa ekler.
#[Signature('telegram:test')]
#[Description('Telegram entegrasyonunu test etmek için mesaj gönderir')]
class TelegramTest extends Command
{
    public function handle(): int
    {
        // Telegram yapılandırması doğrulanır
        $token = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            $this->error('Telegram yapılandırması eksik.');
            $this->line('Lütfen .env dosyasında <comment>TELEGRAM_TOKEN</comment> ve <comment>TELEGRAM_CHAT_ID</comment> değerlerini ayarlayın.');

            return self::FAILURE;
        }

        $this->info('Telegram yapılandırması doğrulandı.');
        $this->line('Token: <comment>'.substr($token, 0, 8).'...'.'</comment>');
        $this->line("Chat ID: <comment>{$chatId}</comment>");
        $this->newLine();

        // Gönderilecek mesaj alınır; boş bırakılırsa varsayılan mesaj kullanılır
        $mesaj = $this->ask(
            'Gönderilecek mesajı girin',
            'Task Orbit sistem testi — '.now()->format('d.m.Y H:i')
        );

        if (! $mesaj) {
            $this->error('Mesaj boş bırakılamaz.');

            return self::FAILURE;
        }

        // Job kuyruğa eklenir; kuyruk worker'ı mesajı gönderir
        SendTelegramMessage::dispatch($mesaj);

        $this->info('✅ Telegram mesajı kuyruğa eklendi.');
        $this->line('Kuyruk worker çalışıyorsa mesaj kısa süre içinde iletilecektir.');
        $this->line('Worker başlatmak için: <comment>php artisan queue:work</comment>');

        return self::SUCCESS;
    }
}
