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

        return $this->microposts->create($attributes);
    }

    public function updateContent(Micropost $micropost, string $content): Micropost
    {
        $this->microposts->update($micropost, [
            'content' => $content,
        ]);

        return $micropost;
    }

    public function delete(Micropost $micropost): void
    {
        if ($micropost->image) {
            Storage::disk('public')->delete($micropost->image);
        }

        $this->microposts->delete($micropost);
    }
}

