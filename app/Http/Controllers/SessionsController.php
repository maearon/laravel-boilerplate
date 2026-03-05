<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WebSessionService;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct(
        private readonly WebSessionService $webSessionService,
    ) {
    /**
     * Create a new controller instance.
     */
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
        // dd($request);
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        $result = $this->webSessionService->attempt(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $remember
        );

        if (($result['inactive'] ?? false) === true) {
            return redirect()->route('root')
                ->with('warning', 'Your account is not activated. Please check your email for the activation link.');
        }

        if (($result['authenticated'] ?? false) === true) {
            return redirect()->intended(route('users.show', auth()->id()))
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
        $this->webSessionService->logout();

        return redirect()->route('root')
            ->with('success', 'You have been logged out.');
    }
}
