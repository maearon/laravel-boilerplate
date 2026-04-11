<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CacheService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Resources\MicropostResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly CacheService $cacheService,
    ) {}

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $page = (int) $request->get('page', 1);
        $users = $this->cacheService->rememberUsersList($perPage, $page);

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

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display the specified user (stats + optional follow state when authenticated).
     */
    public function show(Request $request, User $user)
    {
        $userModel = $this->cacheService->rememberUserProfile($user->id, function () use ($user) {
            $fresh = User::query()->findOrFail($user->id);
            $fresh->loadCount(['following', 'followers', 'microposts']);

            return $fresh;
        });

        return new UserProfileResource($userModel);
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

        return new UserResource($user->fresh());
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
    public function microposts(Request $request, User $user)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $page = (int) $request->get('page', 1);
        $microposts = $this->cacheService->rememberUserMicroposts($user->id, $perPage, $page);

        return MicropostResource::collection($microposts);
    }

    /**
     * Display the users that the specified user is following.
     */
    public function following(Request $request, User $user)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $page = (int) $request->get('page', 1);
        $following = $this->cacheService->rememberUserFollowing($user->id, $perPage, $page);

        return UserResource::collection($following);
    }

    /**
     * Display the users that are following the specified user.
     */
    public function followers(Request $request, User $user)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $page = (int) $request->get('page', 1);
        $followers = $this->cacheService->rememberUserFollowers($user->id, $perPage, $page);

        return UserResource::collection($followers);
    }
}
