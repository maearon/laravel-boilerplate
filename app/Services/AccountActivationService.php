<?php

namespace App\Services;

use App\Mail\AccountActivation;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountActivationService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function register(array $data): User
    {
        return $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'activation_digest' => Str::random(60),
        ]);
    }

    public function sendActivationEmail(User $user): void
    {
        // 🔥 FIX: luôn tạo lại token khi gửi mail
        $user->activation_digest = \Illuminate\Support\Str::random(60);
        $user->save();
        Mail::to($user->email)->queue(new AccountActivation($user));
    }

    public function activate(string $token): ?User
    {
        $user = $this->users->findByActivationDigest($token);
        if (!$user) {
            return null;
        }

        $this->users->update($user, [
            'activated' => true,
            'activated_at' => now(),
            'activation_digest' => null,
        ]);

        Auth::login($user);

        return $user;
    }
}

