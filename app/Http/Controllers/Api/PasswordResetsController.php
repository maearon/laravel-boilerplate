<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetsController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $passwordResetService,
    ) {}

    /**
     * POST /api/password_resets
     * Gửi email reset password
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'password_reset.email' => 'required|email|exists:users,email',
        ]);

        $email = $validated['password_reset']['email'];

        $this->passwordResetService->sendResetLink($email);

        return response()->json([
            'flash' => ['success', 'Email sent with password reset instructions']
        ]);
    }

    /**
     * PATCH /api/password_resets/{token}
     * Reset password
     */
    public function update(Request $request, $token)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'user.password' => 'required|min:6|confirmed',
        ]);

        $email = $validated['email'];
        $password = $validated['user']['password'];

        $user = $this->passwordResetService->resetPassword(
            $email,
            (string) $token,
            $password,
            2
        );

        if (!$user) {
            return response()->json([
                'message' => 'Invalid token or expired',
                'errors' => [
                    'token' => ['Invalid password reset token or token has expired.']
                ]
            ], 422);
        }

        return response()->json([
            'flash' => ['success', 'Password has been reset successfully.']
        ]);
    }
}
