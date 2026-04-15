<?php

namespace App\Http\Controllers;

use App\Models\TaskSubmission;
use Illuminate\Http\Request;

class TaskSubmissionController extends Controller
{
    // Bir task'a yapılan tüm teslimleri listeler.
    // Mentor "bu görevi kimler teslim etti?" diye sorar.
    // task_id ile filtreler, intern_register bilgisini de getirir (kim teslim etti).
    public function index(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id'
        ]);

        $submissions = TaskSubmission::with('intern_register')
            ->where('task_id', $validated['task_id'])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Submissions retrieved successfully',
            'data' => $submissions
        ]);
    }

    // Stajyer görevi teslim eder.
    // intern_register_id → bu stajyerin bu staja kayıtlı olduğunu kanıtlar.
    // task_id → hangi görevi teslim ettiği.
    // point ve status başlangıçta 0 ve false — mentor sonra dolduracak.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'intern_register_id' => 'required|exists:intern_registers,id',
            'task_id'            => 'required|exists:tasks,id',
            'submissions'        => 'required|string',
        ]);

        // point ve status mentor tarafından doldurulacak, şimdi default değerler
        $submission = TaskSubmission::create([
            ...$validated,
            'point'  => 0,
            'status' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Submission created successfully',
            'data' => $submission
        ], 201);
    }

    // Tek bir teslimi getirir.
    // task() ilişkisi TaskSubmission modelinde tanımlı → hangi göreve ait olduğunu da getirir.
    public function show(TaskSubmission $taskSubmission)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Submission retrieved successfully',
            'data' => $taskSubmission->load('task')
        ]);
    }

    // Mentor teslimi değerlendirir: puan verir ve status'u günceller.
    // sometimes → sadece gönderilen alanı günceller, ikisini birden göndermek zorunda değil.
    // point en az 0, en fazla 100 olabilir.
    public function update(Request $request, TaskSubmission $taskSubmission)
    {
        $validated = $request->validate([
            'point'  => 'sometimes|integer|min:0|max:100',
            'status' => 'sometimes|boolean',
        ]);

        $taskSubmission->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Submission updated successfully',
            'data' => $taskSubmission
        ]);
    }
}
