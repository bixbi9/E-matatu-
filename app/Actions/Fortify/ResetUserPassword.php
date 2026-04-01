<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use RuntimeException;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        $supabase = app(SupabaseAuthService::class);

        if ($supabase->hasAdminConfiguration() && ! empty($user->supabase_user_id)) {
            try {
                $supabase->updateUser($user->supabase_user_id, [
                    'password' => $input['password'],
                ]);
            } catch (RuntimeException $e) {
                throw ValidationException::withMessages([
                    'password' => $e->getMessage(),
                ]);
            }
        }
    }
}
