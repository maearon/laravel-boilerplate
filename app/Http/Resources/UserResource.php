<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use Concerns\Gravatar;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->name,
            'email' => $this->email,
            'activated' => $this->activated,
            'createdAt' => $this->created_at?->toIso8601String(),
            'gravatar' => self::gravatarUrl($this->email, 80),
        ];
    }
}
