<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginateActivated(int $perPage = 10): LengthAwarePaginator;

    public function findOrFail(int $id): User;

    public function findByEmail(string $email): ?User;

    public function findByActivationDigest(string $token): ?User;

    public function findByValidResetDigest(string $token, int $expiresHours = 2): ?User;

    public function findByValidResetToken(string $email, string $token, int $expiresHours = 2): ?User;

    public function create(array $attributes): User;

    public function update(User $user, array $attributes): bool;

    public function delete(User $user): ?bool;

    public function paginateMicroposts(User $user, int $perPage = 10): LengthAwarePaginator;

    public function paginateFollowing(User $user, int $perPage = 10): LengthAwarePaginator;

    public function paginateFollowers(User $user, int $perPage = 10): LengthAwarePaginator;
}

