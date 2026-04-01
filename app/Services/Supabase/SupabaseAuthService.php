<?php

namespace App\Services\Supabase;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SupabaseAuthService
{
    public function hasAdminConfiguration(): bool
    {
        return filled(config('services.supabase.url'))
            && filled(config('services.supabase.service_role_key'));
    }

    public function hasSessionConfiguration(): bool
    {
        return filled(config('services.supabase.url'))
            && filled(config('services.supabase.anon_key'));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function createUser(array $payload): array
    {
        return $this->sendAdminRequest('post', '/auth/v1/admin/users', $payload, 'user creation');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateUser(string $supabaseUserId, array $payload): array
    {
        return $this->sendAdminRequest('put', "/auth/v1/admin/users/{$supabaseUserId}", $payload, 'user update');
    }

    /**
     * @return array<string, mixed>
     */
    public function signInWithPassword(string $email, string $password): array
    {
        if (! $this->hasSessionConfiguration()) {
            throw new RuntimeException('Supabase session configuration is missing. Fill in SUPABASE_URL and SUPABASE_ANON_KEY.');
        }

        $response = Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'apikey' => (string) config('services.supabase.anon_key'),
            ])
            ->post('/auth/v1/token?grant_type=password', [
                'email' => $email,
                'password' => $password,
            ]);

        return $this->decodeResponse($response->status(), $response->json(), 'sign-in');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function sendAdminRequest(string $method, string $path, array $payload, string $action): array
    {
        if (! $this->hasAdminConfiguration()) {
            throw new RuntimeException('Supabase admin configuration is missing. Fill in SUPABASE_URL and SUPABASE_SERVICE_ROLE_KEY.');
        }

        $serviceRoleKey = (string) config('services.supabase.service_role_key');

        $response = Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'apikey' => $serviceRoleKey,
                'Authorization' => "Bearer {$serviceRoleKey}",
            ])
            ->send($method, $path, [
                'json' => $payload,
            ]);

        return $this->decodeResponse($response->status(), $response->json(), $action);
    }

    /**
     * @param  array<string, mixed>|null  $json
     * @return array<string, mixed>
     */
    protected function decodeResponse(int $status, ?array $json, string $action): array
    {
        if ($status >= 200 && $status < 300) {
            return $json ?? [];
        }

        $message = $json['msg']
            ?? $json['message']
            ?? $json['error_description']
            ?? $json['error']
            ?? "Supabase {$action} failed with status {$status}.";

        throw new RuntimeException("Supabase {$action} failed: {$message}");
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('services.supabase.url'), '/');
    }
}
