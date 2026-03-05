<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = $this->userService->paginateActivated(10);
        // return response()->json($users);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = $this->userService->createActivated($request->only(['name', 'email', 'password']));

        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);
        $this->userService->update($user, $request->only(['name', 'email', 'password']));

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return response()->json(null, 204);
    }

    /**
     * Display the microposts for the specified user.
     */
    public function microposts(User $user)
    {
        $microposts = $this->userService->paginateMicroposts($user, 10);
        return response()->json($microposts);
    }

    /**
     * Display the users that the specified user is following.
     */
    public function following(User $user)
    {
        $following = $this->userService->paginateFollowing($user, 10);
        return response()->json($following);
    }

    /**
     * Display the users that are following the specified user.
     */
    public function followers(User $user)
    {
        $followers = $this->userService->paginateFollowers($user, 10);
        return response()->json($followers);
    }
}
