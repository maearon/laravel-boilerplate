<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    private const TTL_USERS_LIST = 300;

    private const TTL_USER_STATS = 60;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Remember paginated activated users (for API / web list).
     */
    public function rememberUsersList(int $perPage, int $page): LengthAwarePaginator
    {
        return Cache::remember(
            "users:activated:per_page:{$perPage}:page:{$page}",
            self::TTL_USERS_LIST,
            fn () => $this->userRepository->paginateActivated($perPage)
        );
    }

    /**
     * Forget users list cache (call on user create/update/delete).
     */
    public function forgetUsersList(): void
    {
        Cache::flush(); // Or use tags: Cache::tags(['users'])->flush();
        // For granular: iterate pages or use a version key
    }

    /**
     * Remember user stats (following/followers count).
     */
    public function rememberUserStats(int $userId): array
    {
        return Cache::remember(
            "user:{$userId}:stats",
            self::TTL_USER_STATS,
            function () use ($userId) {
                $user = $this->userRepository->findOrFail($userId);

                return [
                    'following_count' => $user->following()->count(),
                    'followers_count' => $user->followers()->count(),
                ];
            }
        );
    }

    /**
     * Forget user stats (call on follow/unfollow).
     */
    public function forgetUserStats(int $userId): void
    {
        Cache::forget("user:{$userId}:stats");
    }
}
