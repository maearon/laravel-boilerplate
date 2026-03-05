<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class RelationshipService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function follow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->follow($user);

        Cache::forget("user:{$actor->id}:stats");
        Cache::forget("user:{$user->id}:stats");
    }

    public function unfollow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->unfollow($user);

        Cache::forget("user:{$actor->id}:stats");
        Cache::forget("user:{$user->id}:stats");
    }
}

