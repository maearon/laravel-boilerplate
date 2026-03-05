<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetsController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $passwordResetService,
    ) {
    /**
     * Create a new controller instance.
     */
        $this->middleware('guest');
    }

    /**
     * Show the form to request a password reset.
     */
    public function create()
    {
        return view('password_resets.create');
    }

    /**
     * Send a password reset email.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        $this->passwordResetService->sendResetLink($request->string('email')->toString());

        return redirect()->route('password.email')
            ->with('info', 'Email sent with password reset instructions');
    }

    /**
     * Show the form to reset a password.
     */
    public function edit($token)
    {
        $user = $this->passwordResetService->findValidUserByResetDigest((string) $token, 2);

        if (!$user) {
            return redirect()->route('password.email')
                ->with('danger', 'Invalid password reset token or token has expired.');
        }

        return view('password_resets.edit', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $user = $this->passwordResetService->resetPassword(
            $request->string('email')->toString(),
            $request->string('token')->toString(),
            $request->string('password')->toString(),
            2
        );

        if (!$user) {
            return redirect()->route('password.email')
                ->with('danger', 'Invalid password reset token or token has expired.');
        }

        return redirect()->route('login')
            ->with('success', 'Password has been reset successfully.');
    }
}
