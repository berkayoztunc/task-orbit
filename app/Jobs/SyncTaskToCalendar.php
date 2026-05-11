<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\GoogleCalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncTaskToCalendar implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        $dueDate = $this->task->due_date ?? $this->task->lesson->start_date;

        $users = $this->task
            ->lesson
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
                    title: 'Ödev: '.$this->task->title,
                    startDateTime: $dueDate.'T09:00:00',
                    endDateTime: $dueDate.'T17:00:00',
                    description: $this->task->description,
                );
            } catch (\Exception) {
                continue;
            }
        }
    }
}
