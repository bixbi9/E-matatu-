<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use RuntimeException;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }

        $supabase = app(SupabaseAuthService::class);

        if ($supabase->hasAdminConfiguration() && ! empty($user->supabase_user_id)) {
            try {
                $supabase->updateUser($user->supabase_user_id, [
                    'email' => $input['email'],
                    'email_confirm' => true,
                    'user_metadata' => [
                        'name' => $input['name'],
                        'user_uid' => $user->user_uid,
                        'local_user_id' => $user->id,
                    ],
                ]);
            } catch (RuntimeException $e) {
                throw ValidationException::withMessages([
                    'email' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
