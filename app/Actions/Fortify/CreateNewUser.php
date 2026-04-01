<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Auth\UserRegistrationService;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use RuntimeException;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        try {
            return app(UserRegistrationService::class)->register($input);
        } catch (RuntimeException $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }
    }
}
