<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\MicropostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Cache-aside pattern for expensive queries.
 * TTL expiration with explicit invalidation on data updates.
 */
class CacheService
{
    private const TTL_USERS_LIST = 300;

    private const TTL_USER_PROFILE = 120;

    private const TTL_USER_STATS = 60;

    private const TTL_MICROPOSTS_INDEX = 180;

    private const CACHE_VERSION_USERS = 'cache:users:version';

    private const CACHE_VERSION_MICROPOSTS = 'cache:microposts:version';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MicropostRepositoryInterface $micropostRepository,
    ) {}

    /**
     * Remember paginated activated users (cache-aside).
     */
    public function rememberUsersList(int $perPage, int $page): LengthAwarePaginator
    {
        $version = Cache::get(self::CACHE_VERSION_USERS, 0);

        return Cache::remember(
            "users:activated:v{$version}:per_page:{$perPage}:page:{$page}",
            self::TTL_USERS_LIST,
            fn () => $this->userRepository->paginateActivated($perPage)
        );
    }

    /**
     * Invalidate users list cache (call on user create/update/delete).
     */
    public function forgetUsersList(): void
    {
        Cache::increment(self::CACHE_VERSION_USERS);
    }

    /**
     * Remember user profile (API show).
     */
    public function rememberUserProfile(int $userId, callable $callback): mixed
    {
        return Cache::remember(
            "user:profile:{$userId}",
            self::TTL_USER_PROFILE,
            $callback
        );
    }

    /**
     * Forget user profile (call on user update/delete).
     */
    public function forgetUserProfile(int $userId): void
    {
        Cache::forget("user:profile:{$userId}");
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

    /**
     * Remember microposts index (API).
     */
    public function rememberMicropostsIndex(int $perPage, int $page): LengthAwarePaginator
    {
        $version = Cache::get(self::CACHE_VERSION_MICROPOSTS, 0);

        return Cache::remember(
            "microposts:index:v{$version}:per_page:{$perPage}:page:{$page}",
            self::TTL_MICROPOSTS_INDEX,
            fn () => $this->micropostRepository->paginateLatestWithUser($perPage)
        );
    }

    /**
     * Invalidate microposts index (call on micropost create/delete).
     */
    public function forgetMicropostsIndex(): void
    {
        Cache::increment(self::CACHE_VERSION_MICROPOSTS);
    }
}
