<?php

namespace App\Repositories;

use App\Models\Micropost;
use App\Repositories\Interfaces\MicropostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MicropostRepository implements MicropostRepositoryInterface
{
    public function paginateLatestWithUser(int $perPage = 10): LengthAwarePaginator
    {
        return Micropost::query()
            ->select(['id', 'content', 'user_id', 'image', 'created_at'])
            ->with(['user:id,name,email'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function create(array $attributes): Micropost
    {
        return Micropost::query()->create($attributes);
    }

    public function update(Micropost $micropost, array $attributes): bool
    {
        return $micropost->update($attributes);
    }

    public function delete(Micropost $micropost): ?bool
    {
        return $micropost->delete();
    }
}

