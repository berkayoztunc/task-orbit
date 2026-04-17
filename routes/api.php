<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
<<<<<<< HEAD
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskSubmissionController;
=======
use App\Models\Role;
>>>>>>> 905f15b77c4e9f94e280d6213e1af25186731b19

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function () {
    return [
        'status' => 'success'
    ];
});

//companies
Route::apiResource('companies', CompanyController::class);

//profiles
Route::apiResource('profiles', ProfileController::class);
//roles
Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{role}', [RoleController::class, 'show']);
//internships
Route::apiResource('internships', InternshipController::class);
Route::get('companies/{id}/profiles', [CompanyController::class, 'profiles']);
Route::get('companies/{id}/internships', [CompanyController::class, 'internships']);

// Roller - index/show
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