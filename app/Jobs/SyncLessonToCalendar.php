<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\GoogleCalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
                    startDateTime: $this->lesson->start_date.'T09:00:00',
                    endDateTime: $this->lesson->end_date.'T17:00:00',
                    description: $this->lesson->description,
                );
            } catch (\Exception) {
                continue;
            }
        }
    }
}
