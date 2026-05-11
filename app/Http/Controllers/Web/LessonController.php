<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    public function show(int $lesson): Response
    {
        return Inertia::render('lessons/show', ['lessonId' => $lesson]);
    }
}
