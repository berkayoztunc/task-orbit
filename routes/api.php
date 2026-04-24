<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InternRegisterController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskSubmissionController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return [
        'status' => 'success',
    ];
});

// Companies
Route::apiResource('companies', CompanyController::class);

// Profiles
Route::apiResource('profiles', ProfileController::class);

// Internships
Route::apiResource('internships', InternshipController::class);
Route::get('companies/{id}/profiles', [CompanyController::class, 'profiles']);
Route::get('companies/{id}/internships', [CompanyController::class, 'internships']);

// Intern Registers
Route::apiResource('intern-registers', InternRegisterController::class);
Route::get('internships/{internship}/intern-registers', [InternRegisterController::class, 'indexByInternship']);

// Lessons
Route::apiResource('lessons', LessonController::class);
Route::get('internships/{internship}/lessons', [LessonController::class, 'indexByInternship']);
Route::post('lessons/{lesson}/send-attendance-check', [LessonController::class, 'sendAttendanceCheck']);

// Roles
Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{role}', [RoleController::class, 'show']);

// Users
Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::patch('users/{user}/switch-profile', [UserController::class, 'switchProfile']);

// Tasks
Route::apiResource('tasks', TaskController::class);

// Task Submissions
Route::get('task-submissions', [TaskSubmissionController::class, 'index']);
Route::post('task-submissions', [TaskSubmissionController::class, 'store']);
Route::get('task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'show']);
Route::patch('task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'update']);

// Media
Route::get('media', [MediaController::class, 'index']);
Route::post('media', [MediaController::class, 'store']);
Route::delete('media/{media}', [MediaController::class, 'destroy']);

// Images
Route::post('images', [ImageController::class, 'store']);
Route::delete('images/{image}', [ImageController::class, 'destroy']);

// Telegram Webhook
Route::post('/webhook/telegram', [TelegramWebhookController::class, 'handle']);
