<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginateActivated(int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->where('activated', true)
            ->paginate($perPage);
    }

    public function findOrFail(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findByActivationDigest(string $token): ?User
    {
        return User::query()->where('activation_digest', $token)->first();
    }

    public function findByValidResetDigest(string $token, int $expiresHours = 2): ?User
    {
        return User::query()
            ->where('reset_digest', $token)
            ->where('reset_sent_at', '>', now()->subHours($expiresHours))
            ->first();
    }

    public function findByValidResetToken(string $email, string $token, int $expiresHours = 2): ?User
    {
        return User::query()
            ->where('email', $email)
            ->where('reset_digest', $token)
            ->where('reset_sent_at', '>', now()->subHours($expiresHours))
            ->first();
    }

    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function update(User $user, array $attributes): bool
    {
        return $user->update($attributes);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    public function paginateMicroposts(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->microposts()->paginate($perPage);
    }

    public function paginateFollowing(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->following()->paginate($perPage);
    }

    public function paginateFollowers(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->followers()->paginate($perPage);
    }
}

