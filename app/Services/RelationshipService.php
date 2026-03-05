<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class RelationshipService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function follow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->follow($user);
    }

    public function unfollow(User $actor, int $followedId): void
    {
        $user = $this->users->findOrFail($followedId);
        $actor->unfollow($user);
    }
}

