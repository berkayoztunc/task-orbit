<?php

namespace App\Observers;

use App\Jobs\SyncTaskToCalendar;
use App\Models\Task;

class TaskObserver
{
    public function created(Task $task): void
    {
        SyncTaskToCalendar::dispatch($task);
    }
}
