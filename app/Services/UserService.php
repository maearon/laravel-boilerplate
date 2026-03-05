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
    ) {}

    public function paginateActivated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateActivated($perPage);
    }

    public function createActivated(array $data): User
    {
        return $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'activated' => true,
            'activated_at' => now(),
        ]);
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

        return $user;
    }

    public function delete(User $user): void
    {
        $this->users->delete($user);
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
}

