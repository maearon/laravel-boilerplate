<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserProfileResource extends JsonResource
{
    use Concerns\Gravatar;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isFollowing = false;
        if ($request->user() && $request->user()->id !== $this->id) {
            $isFollowing = $request->user()->isFollowing($this->resource);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'following' => $this->following_count,
            'followers' => $this->followers_count,
            'micropost' => $this->microposts_count,
            'gravatar' => self::gravatarUrl($this->email, 80),
            'currentUserFollowingUser' => $isFollowing,
        ];
    }
}
