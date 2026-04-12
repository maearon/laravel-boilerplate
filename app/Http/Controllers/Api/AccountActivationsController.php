<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountActivationService;
use Illuminate\Http\Request;

class AccountActivationsController extends Controller
{
    public function __construct(
        private readonly AccountActivationService $accountActivationService,
    ) {}

    /**
     * POST /api/account_activations
     * Gửi lại email kích hoạt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resend_activation_email.email' => 'required|email|exists:users,email',
        ]);

        $email = $validated['resend_activation_email']['email'];

        $user = User::where('email', $email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Account already activated'
            ], 422);
        }

        $this->accountActivationService->sendActivationEmail($user);

        return response()->json([
            'flash' => ['success', 'Activation email sent']
        ]);
    }

    /**
     * PATCH /api/account_activations/{token}
     * Kích hoạt tài khoản
     */
    public function update(Request $request, $token)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $validated['email'];

        $user = $this->accountActivationService->activate((string) $token);

        if (!$user || $user->email !== $email) {
            return response()->json([
                'message' => 'Invalid activation link',
                'errors' => [
                    'token' => ['Invalid or expired activation token']
                ]
            ], 422);
        }

        return response()->json([
            'user' => $user,
            'flash' => ['success', 'Account activated successfully!']
        ]);
    }
}
