<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update supabase_user_id with Google's ID if not already set
            if (! $user->supabase_user_id) {
                $user->update(['supabase_user_id' => $googleUser->getId()]);
            }
        } else {
            $uid = (string) Str::uuid();
            $user = User::create([
                'name'             => $googleUser->getName(),
                'email'            => $googleUser->getEmail(),
                'user-id'          => 'google_' . $googleUser->getId(),
                'user_uid'         => $uid,
                'supabase_user_id' => $googleUser->getId(),
                'password'         => Str::random(32),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
