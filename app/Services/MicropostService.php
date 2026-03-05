<?php

namespace App\Services;

use App\Models\Micropost;
use App\Repositories\Interfaces\MicropostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MicropostService
{
    public function __construct(
        private readonly MicropostRepositoryInterface $microposts,
        private readonly CacheService $cache,
    ) {}

    public function paginateLatestWithUser(int $perPage = 10): LengthAwarePaginator
    {
        return $this->microposts->paginateLatestWithUser($perPage);
    }

    public function createForUser(int $userId, string $content, ?UploadedFile $image = null): Micropost
    {
        $attributes = [
            'content' => $content,
            'user_id' => $userId,
        ];

        if ($image) {
            $attributes['image'] = $image->store('microposts', 'public');
        }

        $micropost = $this->microposts->create($attributes);

        $this->cache->forgetMicropostsIndex();
        $this->cache->forgetUserMicroposts($userId);

        return $micropost;
    }

    public function updateContent(Micropost $micropost, string $content): Micropost
    {
        $this->microposts->update($micropost, [
            'content' => $content,
        ]);

        $this->cache->forgetMicropostShow($micropost->id);

        return $micropost;
    }

    public function delete(Micropost $micropost): void
    {
        if ($micropost->image) {
            Storage::disk('public')->delete($micropost->image);
        }

        $this->microposts->delete($micropost);

        $this->cache->forgetMicropostsIndex();
        $this->cache->forgetUserMicroposts($micropost->user_id);
        $this->cache->forgetMicropostShow($micropost->id);
    }
}

