<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if (! $request->user()->current_profile_id) {
            return redirect()->route('page.profile.select');
        }

        return Inertia::render('dashboard', [
            'hasGoogleCalendar' => (bool) $request->user()->google_token,
        ]);
    }
}
