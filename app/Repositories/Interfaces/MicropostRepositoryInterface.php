<?php

namespace App\Repositories\Interfaces;

use App\Models\Micropost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MicropostRepositoryInterface
{
    public function paginateLatestWithUser(int $perPage = 10): LengthAwarePaginator;

    public function create(array $attributes): Micropost;

    public function update(Micropost $micropost, array $attributes): bool;

    public function delete(Micropost $micropost): ?bool;
}

