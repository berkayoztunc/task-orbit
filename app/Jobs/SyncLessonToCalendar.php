<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncLessonToCalendar implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lesson $lesson) {}

    public function handle(): void
    {
        $users = $this->lesson
            ->internship
            ->internRegisters()
            ->with('profile.user')
            ->get()
            ->map(fn ($register) => $register->profile->user)
            ->filter(fn ($user) => $user && $user->google_token);

        foreach ($users as $user) {
            try {
                $service = new GoogleCalendarService($user);
                $service->createEvent(
                    title: $this->lesson->title,
                    startDateTime: Carbon::parse($this->lesson->start_date)->toRfc3339String(),
                    endDateTime: Carbon::parse($this->lesson->end_date)->toRfc3339String(),
                    description: $this->lesson->description,
                );
            } catch (\Exception $e) {
                Log::error('SyncLessonToCalendar failed', ['error' => $e->getMessage()]);

                continue;
            }
        }
    }
}
