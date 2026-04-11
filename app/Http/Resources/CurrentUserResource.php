<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
{
    use Concerns\Gravatar;

    /**
     * Authenticated user with counts for SPA shell (home sidebar).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'following' => $user->following_count,
            'followers' => $user->followers_count,
            'micropost' => $user->microposts_count,
            'gravatar' => self::gravatarUrl($user->email, 80),
        ];
    }
}
