<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $data = $request->all();
        $message = $data['message'] ?? null;

        if (! $message) {
            return response()->json(['ok' => true]);
        }

        $chatId = (string) $message['chat']['id'];
        $text = trim($message['text'] ?? '');
        $lowerText = mb_strtolower($text);

        $registeredUser = User::where('telegram_chat_id', $chatId)->first();

        if ($registeredUser) {
            $lessonSelectKey = "lesson_select:{$chatId}";
            $pendingLessons = Cache::get($lessonSelectKey);

            if ($lowerText === 'evet' || $lowerText === 'hayır' || $lowerText === 'hayir') {
                $this->handleAttendanceResponse($chatId, $lowerText === 'evet', $registeredUser);
            } elseif ($lowerText === 'yoklama') {
                $this->handleListLessons($chatId, $registeredUser, $lessonSelectKey);
            } elseif ($pendingLessons && preg_match('/^\d+$/', $text)) {
                $index = (int) $text - 1;
                if (isset($pendingLessons[$index])) {
                    Cache::forget($lessonSelectKey);
                    $this->handleSendAttendanceCheck($chatId, $pendingLessons[$index], $registeredUser);
                } else {
                    $this->sendMessage($chatId, 'Geçersiz numara. Lütfen listeden bir numara seçin.');
                }
            } else {
                $this->sendMessage($chatId, "Geçersiz komut.\n- Yoklama başlatmak için: 'yoklama'\n- Yoklamayı yanıtlamak için: 'evet' veya 'hayır'");
            }
        } else {
            $this->handleRegistration($chatId, $text);
        }

        return response()->json(['ok' => true]);
    }

    private function handleRegistration(string $chatId, string $text): void
    {
        $user = User::where('email', $text)->first();

        if (! $user) {
            $this->sendMessage($chatId, 'E-posta adresiniz sistemde bulunamadı. Lütfen kayıtlı e-posta adresinizi gönderin.');

            return;
        }

        $user->update(['telegram_chat_id' => $chatId]);
        $this->sendMessage($chatId, "Merhaba {$user->name}! Telegram hesabınız sisteme bağlandı. Artık yoklama bildirimlerini alabilirsiniz.");
    }

    private function handleListLessons(string $chatId, User $user, string $cacheKey): void
    {
        $isMentor = $user->profiles()->where('role_id', 1)->exists();

        if (! $isMentor) {
            $this->sendMessage($chatId, 'Bu komut yalnızca mentörlere özeldir.');

            return;
        }

        $mentorProfileIds = $user->profiles()->where('role_id', 1)->pluck('id');
        $lessons = Lesson::whereIn('profile_id', $mentorProfileIds)->get(['id', 'title']);

        if ($lessons->isEmpty()) {
            $this->sendMessage($chatId, 'Atanmış dersiniz bulunamadı.');

            return;
        }

        $lessonIds = $lessons->pluck('id')->toArray();
        Cache::put($cacheKey, $lessonIds, now()->addMinutes(5));

        $list = $lessons->map(fn ($l, $i) => ($i + 1).'. '.$l->title)->implode("\n");
        $this->sendMessage($chatId, "📚 Dersleriniz:\n{$list}\n\nYoklama başlatmak için ders numarasını yazın.");
    }

    private function handleSendAttendanceCheck(string $chatId, int $lessonId, User $user): void
    {
        $lesson = Lesson::find($lessonId);

        if (! $lesson) {
            $this->sendMessage($chatId, 'Ders bulunamadı.');

            return;
        }

        $internRegisters = $lesson->internship->internRegisters()->with('profile.user')->get();
        $sent = 0;

        foreach ($internRegisters as $register) {
            $intern = $register->profile?->user;

            if (! $intern || ! $intern->telegram_chat_id) {
                continue;
            }

            Attendance::firstOrCreate(
                ['intern_register_id' => $register->id, 'lesson_id' => $lesson->id],
                ['status' => null]
            );

            self::sendMessage(
                $intern->telegram_chat_id,
                "📚 Yoklama\nDers: {$lesson->title}\n\nDerse katılıyor musun?\n'evet' veya 'hayır' yazarak cevapla."
            );

            $sent++;
        }

        $this->sendMessage($chatId, "{$sent} stajyere yoklama sorusu gönderildi.");
    }

    private function handleAttendanceResponse(string $chatId, bool $status, User $user): void
    {
        $profileIds = $user->profiles()->pluck('id');

        $pendingAttendance = Attendance::whereNull('status')
            ->whereHas('intern_register', fn ($q) => $q->whereIn('profile_id', $profileIds))
            ->with(['lesson', 'intern_register.profile.user'])
            ->latest('id')
            ->first();

        if (! $pendingAttendance) {
            $this->sendMessage($chatId, 'Bekleyen bir yoklama bulunamadı.');

            return;
        }

        $pendingAttendance->update(['status' => $status]);

        $cevap = $status ? 'Katılıyor ✅' : 'Katılmıyor ❌';
        $this->sendMessage($chatId, "Cevabınız kaydedildi: {$cevap}");

        // Mentoru bilgilendir
        $lesson = $pendingAttendance->lesson;
        $mentorProfile = $lesson->profile;
        $mentor = $mentorProfile?->user;

        if ($mentor && $mentor->telegram_chat_id) {
            $internName = $user->name ?? $user->email;

            $lessonTitle = $lesson->title;
            $this->sendMessage(
                $mentor->telegram_chat_id,
                "📋 Yoklama Güncellendi\nDers: {$lessonTitle}\nStajyer: {$internName}\nDurum: {$cevap}"
            );
        }
    }

    public static function sendMessage(string $chatId, string $text): void
    {
        $token = config('services.telegram.token');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
