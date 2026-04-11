<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MicropostResource extends JsonResource
{
    use Concerns\Gravatar;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->whenLoaded('user');

        return [
            'id' => $this->id,
            'content' => $this->content,
            'createdAt' => $this->created_at?->toIso8601String(),
            'imageUrl' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gravatar' => self::gravatarUrl($user->email, 50),
            ] : null,
        ];
    }
}
