<?php

namespace App\Observers;

use App\Jobs\SyncLessonToCalendar;
use App\Models\Lesson;

class LessonObserver
{
    public function created(Lesson $lesson): void
    {
        SyncLessonToCalendar::dispatch($lesson);
    }
}
