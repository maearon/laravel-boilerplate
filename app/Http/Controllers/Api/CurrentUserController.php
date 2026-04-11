<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentUserResource;
use App\Services\CacheService;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    public function __construct(
        private readonly CacheService $cacheService,
    ) {}

    /**
     * Return the authenticated user (cached for high traffic).
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $cached = $this->cacheService->rememberCurrentUser($user->id, function () use ($user) {
            $user->loadCount(['microposts', 'following', 'followers']);

            return $user;
        });

        return new CurrentUserResource($cached);
    }
}
