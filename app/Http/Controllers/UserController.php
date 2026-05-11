<?php

namespace App\Http\Controllers;

use App\Models\InternRegister;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tüm kullanıcıları listeler.
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'data' => User::all(),
        ]);
    }

    // Tek bir kullanıcıyı profilleriyle birlikte getirir.
    public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $user->load('profiles.company', 'profiles.role'),
        ]);
    }

    // Mevcut kimliği doğrulanmış kullanıcının tam bağlamını döndürür.
    // Aktif profil, şirket, rol ve onaylı staj kaydını içerir.
    public function me(Request $request)
    {
        $user = $request->user()->load('profiles.company', 'profiles.role');

        $activeProfile = $user->profiles
            ->firstWhere('id', $user->current_profile_id);

        $activeInternship = null;
        if ($activeProfile) {
            $activeInternship = InternRegister::with('internship')
                ->where('profile_id', $activeProfile->id)
                ->where('status', 1)
                ->latest()
                ->first();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User context retrieved successfully',
            'data' => [
                'user' => $user,
                'active_profile' => $activeProfile,
                'active_internship' => $activeInternship,
            ],
        ]);
    }

    // Kullanıcının aktif profilini değiştirir.
    public function switchProfile(Request $request, User $user)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $owns = $user->profiles()->where('id', $validated['profile_id'])->exists();

        if (! $owns) {
            return response()->json([
                'status' => 'error',
                'message' => 'This profile does not belong to this user',
            ], 403);
        }

        $user->update(['current_profile_id' => $validated['profile_id']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile switched successfully',
            'data' => $user->fresh()->load('profiles.company', 'profiles.role'),
        ]);
    }
}
