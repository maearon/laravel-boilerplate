<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\AccountActivation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['index', 'show', 'create', 'store', 'activate']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);

        $this->middleware('verified', [
            'only' => ['edit', 'update']
        ]);
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::where('activated', true)->paginate(10);

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activation_digest' => Str::random(60),
        ]);

        // Send activation email
        Mail::to($user->email)->send(new AccountActivation($user));

        return redirect()->route('root')
            ->with('info', 'Please check your email to activate your account.');
    }

    /**
     * Activate a user account.
     */
    public function activate($token)
    {
        $user = User::where('activation_digest', $token)->first();

        if (!$user) {
            return redirect()->route('root')
                ->with('danger', 'Invalid activation link.');
        }

        $user->update([
            'activated' => true,
            'activated_at' => now(),
            'activation_digest' => null,
        ]);

        Auth::login($user);

        return redirect()->route('users.show', $user)
            ->with('success', 'Account activated successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $microposts = $user->microposts()->paginate(10);

        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Display the users that the specified user is following.
     */
    public function following(User $user)
    {
        $users = $user->following()->paginate(10);
        $title = 'Following';

        return view('users.show_follow', [
            'user' => $user,
            'users' => $users,
            'title' => $title
        ]);
    }

    /**
     * Display the users that are following the specified user.
     */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = 'Followers';

        return view('users.show_follow', [
            'user' => $user,
            'users' => $users,
            'title' => $title
        ]);
    }
}
