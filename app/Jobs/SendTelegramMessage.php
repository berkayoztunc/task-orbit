<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public string $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle(): void
    {
        $token = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $this->message,
            ]);

            if (! $response->successful()) {
                Log::error('Telegram message failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram message exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
