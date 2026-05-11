<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
                    startDateTime: Carbon::parse($dueDate)->toRfc3339String(),
                    endDateTime: Carbon::parse($dueDate)->addHour()->toRfc3339String(),
                    description: $this->task->description,
                );
            } catch (\Exception $e) {
                Log::error('SyncTaskToCalendar failed', ['error' => $e->getMessage()]);

                continue;
            }
        }
    }
}
