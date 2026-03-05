<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly CacheService $cache,
    ) {}

    public function paginateActivated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateActivated($perPage);
    }

    public function createActivated(array $data): User
    {
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'activated' => true,
            'activated_at' => now(),
        ]);

        $this->cache->forgetUsersList();

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $this->users->update($user, $attributes);

        $this->cache->forgetUsersList();
        $this->cache->forgetUserProfile($user->id);
        $this->cache->forgetUserStats($user->id);

        return $user;
    }

    public function delete(User $user): void
    {
        $this->users->delete($user);

        $this->cache->forgetUsersList();
        $this->cache->forgetUserProfile($user->id);
        $this->cache->forgetUserStats($user->id);
    }

    public function paginateMicroposts(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateMicroposts($user, $perPage);
    }

    public function paginateFollowing(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateFollowing($user, $perPage);
    }

    public function paginateFollowers(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateFollowers($user, $perPage);
    }

    /**
     * Get IDs of users that the current user follows (for N+1 avoidance in list views).
     */
    public function getFollowingIdsFor(?User $user): \Illuminate\Support\Collection
    {
        if (!$user) {
            return collect();
        }

        return $user->following()->pluck('users.id');
    }
}

