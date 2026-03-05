<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class WebSessionService
{
    public function attempt(string $email, string $password, bool $remember): array
    {
        $ok = Auth::attempt(['email' => $email, 'password' => $password], $remember);
        if (!$ok) {
            return ['authenticated' => false, 'activated' => false];
        }

        $user = Auth::user();
        if (!$user?->activated) {
            Auth::logout();
            return ['authenticated' => false, 'activated' => false, 'inactive' => true];
        }

        return ['authenticated' => true, 'activated' => true];
    }

    public function logout(): void
    {
        Auth::logout();
    }
}

