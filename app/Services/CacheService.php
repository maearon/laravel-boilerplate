<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\MicropostRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Cache-aside pattern for expensive queries.
 * TTL expiration with explicit invalidation on data updates.
 * Optimized for high read traffic (10x+).
 */
class CacheService
{
    private const TTL_USERS_LIST = 300;

    private const TTL_USER_PROFILE = 120;

    private const TTL_USER_STATS = 60;

    private const TTL_MICROPOSTS_INDEX = 180;

    private const TTL_USER_MICROPOSTS = 120;

    private const TTL_USER_FOLLOWING = 120;

    private const TTL_USER_FOLLOWERS = 120;

    private const TTL_MICROPOST_SHOW = 300;

    private const TTL_CURRENT_USER = 30;

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

    /**
     * Remember user microposts (API users/{user}/microposts).
     */
    public function rememberUserMicroposts(int $userId, int $perPage, int $page): LengthAwarePaginator
    {
        $version = Cache::get("cache:user:{$userId}:microposts:version", 0);

        return Cache::remember(
            "user:{$userId}:microposts:v{$version}:p{$perPage}:page{$page}",
            self::TTL_USER_MICROPOSTS,
            function () use ($userId, $perPage) {
                $user = $this->userRepository->findOrFail($userId);

                return $this->userRepository->paginateMicroposts($user, $perPage);
            }
        );
    }

    public function forgetUserMicroposts(int $userId): void
    {
        Cache::increment("cache:user:{$userId}:microposts:version");
    }

    /**
     * Remember user following (API users/{user}/following).
     */
    public function rememberUserFollowing(int $userId, int $perPage, int $page): LengthAwarePaginator
    {
        $version = Cache::get("cache:user:{$userId}:following:version", 0);

        return Cache::remember(
            "user:{$userId}:following:v{$version}:p{$perPage}:page{$page}",
            self::TTL_USER_FOLLOWING,
            function () use ($userId, $perPage) {
                $user = $this->userRepository->findOrFail($userId);

                return $this->userRepository->paginateFollowing($user, $perPage);
            }
        );
    }

    /**
     * Remember user followers (API users/{user}/followers).
     */
    public function rememberUserFollowers(int $userId, int $perPage, int $page): LengthAwarePaginator
    {
        $version = Cache::get("cache:user:{$userId}:followers:version", 0);

        return Cache::remember(
            "user:{$userId}:followers:v{$version}:p{$perPage}:page{$page}",
            self::TTL_USER_FOLLOWERS,
            function () use ($userId, $perPage) {
                $user = $this->userRepository->findOrFail($userId);

                return $this->userRepository->paginateFollowers($user, $perPage);
            }
        );
    }

    public function forgetUserFollowing(int $userId): void
    {
        Cache::increment("cache:user:{$userId}:following:version");
    }

    public function forgetUserFollowers(int $userId): void
    {
        Cache::increment("cache:user:{$userId}:followers:version");
    }

    /**
     * Remember micropost show (API microposts/{id}).
     */
    public function rememberMicropostShow(int $micropostId, callable $callback): mixed
    {
        return Cache::remember(
            "micropost:show:{$micropostId}",
            self::TTL_MICROPOST_SHOW,
            $callback
        );
    }

    public function forgetMicropostShow(int $micropostId): void
    {
        Cache::forget("micropost:show:{$micropostId}");
    }

    /**
     * Remember current user (API /user) - short TTL for freshness.
     */
    public function rememberCurrentUser(int $userId, callable $callback): User
    {
        return Cache::remember(
            "current_user:{$userId}",
            self::TTL_CURRENT_USER,
            $callback
        );
    }

    public function forgetCurrentUser(int $userId): void
    {
        Cache::forget("current_user:{$userId}");
    }
}
