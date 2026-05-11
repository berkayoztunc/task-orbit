<?php

use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\CompanyController as WebCompanyController;
use App\Http\Controllers\Web\InternshipController as WebInternshipController;
use App\Http\Controllers\Web\ProfileController as WebProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Profile selection — entry point after login
    Route::get('profile/select', [WebProfileController::class, 'select'])->name('page.profile.select');

    // Companies
    Route::get('companies', [WebCompanyController::class, 'index'])->name('page.companies');

    // Internships
    Route::get('internships', [WebInternshipController::class, 'index'])->name('page.internships');
    Route::get('internships/{internship}', [WebInternshipController::class, 'show'])->name('page.internships.show');
    Route::get('internships/{internship}/lessons', [WebInternshipController::class, 'lessons'])->name('page.internships.lessons');
});

// OAuth
Route::get('/auth/github', [GithubController::class, 'redirect'])->name('github.redirect');
Route::get('/auth/github/callback', [GithubController::class, 'callback'])->name('github.callback');
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__.'/settings.php';
