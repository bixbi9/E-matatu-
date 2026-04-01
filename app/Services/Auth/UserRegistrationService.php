<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRegistrationService
{
    public function __construct(
        private readonly SupabaseAuthService $supabase,
    ) {
    }

    /**
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    public function register(array $attributes): User
    {
        return DB::transaction(function () use ($attributes) {
            $userUid = (string) Str::uuid();

            $user = User::create([
                'name' => $attributes['name'],
                'email' => strtolower($attributes['email']),
                'user-id' => $userUid,
                'user_uid' => $userUid,
                'password' => Hash::make($attributes['password']),
            ]);

            if (! $this->supabase->hasAdminConfiguration()) {
                return $user;
            }

            $remoteUser = $this->supabase->createUser([
                'email' => strtolower($attributes['email']),
                'password' => $attributes['password'],
                'email_confirm' => true,
                'user_metadata' => [
                    'name' => $attributes['name'],
                    'user_uid' => $userUid,
                    'local_user_id' => $user->id,
                ],
            ]);

            $user->forceFill([
                'supabase_user_id' => $remoteUser['id'] ?? null,
                'supabase_synced_at' => now(),
            ])->save();

            return $user;
        });
    }
}
