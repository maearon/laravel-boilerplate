<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

class PasswordResetsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
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

        $user = User::where('email', $request->email)->first();
        $user->reset_digest = Str::random(60);
        $user->reset_sent_at = now();
        $user->save();

        Mail::to($user->email)->send(new PasswordReset($user));

        return redirect()->route('password.email')
            ->with('info', 'Email sent with password reset instructions');
    }

    /**
     * Show the form to reset a password.
     */
    public function edit($token)
    {
        $user = User::where('reset_digest', $token)
                    ->where('reset_sent_at', '>', now()->subHours(2))
                    ->first();

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

        $user = User::where('email', $request->email)
                    ->where('reset_digest', $request->token)
                    ->where('reset_sent_at', '>', now()->subHours(2))
                    ->first();

        if (!$user) {
            return redirect()->route('password.email')
                ->with('danger', 'Invalid password reset token or token has expired.');
        }

        $user->password = Hash::make($request->password);
        $user->reset_digest = null;
        $user->reset_sent_at = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password has been reset successfully.');
    }
}
