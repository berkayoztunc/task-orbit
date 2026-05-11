<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InternshipController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('internships/index');
    }

    public function show(int $internship): Response
    {
        return Inertia::render('internships/show', ['internshipId' => $internship]);
    }

    public function lessons(int $internship): Response
    {
        return Inertia::render('internships/lessons', ['internshipId' => $internship]);
    }
}
