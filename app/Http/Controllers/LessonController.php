<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Internship;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Lessons retrieved successfully',
            'data' => $lessons, // Replace with actual lessons data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'internship_id' => 'required|exists:internships,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'string',
            'content' => 'string',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $lesson = Lesson::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson created successfully',
            'data' => $lesson, // Replace with actual lesson data
        ]);
    }

    public function show(Lesson $lesson)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lesson retrieved successfully',
            'data' => $lesson, // Replace with actual lesson data
        ]);
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'internship_id' => 'sometimes|exists:internships,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'profile_id' => 'sometimes|exists:profiles,id',
        ]);

        $lesson->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson updated successfully',
            'data' => $lesson, // Replace with actual lesson data
        ]);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson deleted successfully',
        ]);
    }

    public function sendAttendanceCheck(Lesson $lesson): JsonResponse
    {
        $internRegisters = $lesson->internship->internRegisters()->with('profile.user')->get();

        $sent = 0;

        foreach ($internRegisters as $register) {
            $user = $register->profile?->user;

            if (! $user || ! $user->telegram_chat_id) {
                continue;
            }

            Attendance::firstOrCreate(
                ['intern_register_id' => $register->id, 'lesson_id' => $lesson->id],
                ['status' => null]
            );

            TelegramWebhookController::sendMessage(
                $user->telegram_chat_id,
                "📚 Yoklama\nDers: {$lesson->title}\n\nDerse katılıyor musun?\n\"evet\" veya \"hayır\" yazarak cevapla."
            );

            $sent++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "{$sent} stajyere yoklama sorusu gönderildi.",
        ]);
    }

    public function indexByInternship(Internship $internship)
    {
        $lessons = $internship->lessons()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lessons retrieved successfully',
            'data' => $lessons, // Replace with actual lessons data
        ]);
    }
}
