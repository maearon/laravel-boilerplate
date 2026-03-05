<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountActivationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AccountActivationService $accountActivationService,
    ) {
    /**
     * Create a new controller instance.
     */
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
        $users = $this->userService->paginateActivated(10);

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
        // dump($request);
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $this->accountActivationService->register($request->only(['name', 'email', 'password']));
        $this->accountActivationService->sendActivationEmail($user);

        return redirect()->route('root')
            ->with('info', 'Please check your email to activate your account.');
    }

    /**
     * Activate a user account.
     */
    public function activate($token)
    {
        $user = $this->accountActivationService->activate((string) $token);
        if (!$user) {
            return redirect()->route('root')
                ->with('danger', 'Invalid activation link.');
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'Account activated successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $microposts = $this->userService->paginateMicroposts($user, 10);

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

        $this->userService->update($user, $request->only(['name', 'email', 'password']));

        return redirect()->route('users.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Display the users that the specified user is following.
     */
    public function following(User $user)
    {
        $users = $this->userService->paginateFollowing($user, 10);
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
        $users = $this->userService->paginateFollowers($user, 10);
        $title = 'Followers';

        return view('users.show_follow', [
            'user' => $user,
            'users' => $users,
            'title' => $title
        ]);
    }
}
