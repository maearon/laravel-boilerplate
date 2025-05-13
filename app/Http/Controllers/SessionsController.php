<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * Show the login form.
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Handle the login request.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->activated) {
                Auth::logout();
                return redirect()->route('root')
                    ->with('warning', 'Your account is not activated. Please check your email for the activation link.');
            }

            return redirect()->intended(route('users.show', Auth::id()))
                ->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Log the user out.
     */
    public function destroy()
    {
        Auth::logout();

        return redirect()->route('root')
            ->with('success', 'You have been logged out.');
    }
}
