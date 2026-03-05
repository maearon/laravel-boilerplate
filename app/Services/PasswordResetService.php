<?php

namespace App\Services;

use App\Mail\PasswordReset;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function sendResetLink(string $email): void
    {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            return;
        }

        $user->reset_digest = Str::random(60);
        $user->reset_sent_at = now();
        $user->save();

        Mail::to($user->email)->queue(new PasswordReset($user));
    }

    public function findValidUserByResetDigest(string $token, int $expiresHours = 2): ?User
    {
        return $this->users->findByValidResetDigest($token, $expiresHours);
    }

    public function resetPassword(string $email, string $token, string $password, int $expiresHours = 2): ?User
    {
        $user = $this->users->findByValidResetToken($email, $token, $expiresHours);
        if (!$user) {
            return null;
        }

        $user->password = Hash::make($password);
        $user->reset_digest = null;
        $user->reset_sent_at = null;
        $user->save();

        return $user;
    }
}

