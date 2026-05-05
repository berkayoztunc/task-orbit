<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect()
    {

        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {

        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect('/auth/github');
        }

        $user = User::updateOrCreate(
            [
                'email' => $githubUser->getEmail(),
                'name' => $githubUser->getName(),
            ],
            [
                'github_username' => $githubUser->getNickname(),
                'github_token' => $githubUser->token,
                'github_avatar' => $githubUser->getAvatar(),
                'password' => bcrypt(str()->random(32)),
            ]
        );

        // Sadece yeni kayıt oluşturulduğunda bildirim gönder
        if ($user->wasRecentlyCreated) {
            SendTelegramMessage::dispatch("Yeni kullanıcı kayıt oldu!\nİsim: {$user->name}\nEmail: {$user->email}\nGitHub: {$user->github_username}");
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
