<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Wraps Sanctum login payload: user + plainTextToken.
 *
 * @property-read array{user: \App\Models\User, token: string} $resource
 */
class LoginResponseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->resource['user']),
            'token' => $this->resource['token'],
        ];
    }
}
