<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendTelegramMessage implements ShouldQueue
{
    use Queueable;

    // PHP 8 constructor property promotion özelliği
    // public string $message yazmak aşağıdaki iki satırın kısaltması:
    // public string $message;
    // $this->message = $message;
    // Yani constructor içi boş görünse de $message otomatik sınıf özelliği olur

    public string $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    // Job kuyruğa alındığında bu fonksiyon tetiklenir
    // Kullanıcıyı bekletmemek için arka planda çalışır
    public function handle(): void
    {
        // .env dosyasından Telegram bot token ve chat id yi al
        // Bu bilgiler asla direkt koda yazılmaz, güvenlik için .env de tutulur
        $token = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');

        // Telegram API'sine HTTP POST isteği at
        // bot{$token} kısmı token'ı URL'e ekler
        // chat_id → mesajın gönderileceği kişi veya grup
        // text → gönderilecek mesaj
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $this->message,
        ]);
    }
}
