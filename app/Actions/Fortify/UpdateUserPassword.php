<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use RuntimeException;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');

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
