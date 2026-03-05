<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class RelationshipService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly CacheService $cache,
    ) {}

    public function follow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->follow($user);

        Cache::forget("user:{$actor->id}:stats");
        Cache::forget("user:{$user->id}:stats");
        $this->cache->forgetUserFollowing($actor->id);
        $this->cache->forgetUserFollowers($user->id);
    }

    public function unfollow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->unfollow($user);

        Cache::forget("user:{$actor->id}:stats");
        Cache::forget("user:{$user->id}:stats");
        $this->cache->forgetUserFollowing($actor->id);
        $this->cache->forgetUserFollowers($user->id);
    }
}

