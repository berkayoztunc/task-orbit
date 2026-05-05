<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Attendance index',
            'data' => $attendances
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required',
            'intern_register_id' => 'sometimes|exists:intern_registers,id',
            'lesson_id' => 'sometimes|exists:lessons,id',
        ]);

        $attendance->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance updated successfully',
            'data' => $attendance
        ]);
    }

    public function indexByLesson(Lesson $lesson)
    {
        $attendances = Attendance::where('lesson_id', $lesson->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance index by lesson',
            'data' => $attendances
        ]);
    }
}
