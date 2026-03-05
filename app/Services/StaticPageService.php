<?php

namespace App\Services;

use App\Models\Micropost;
use App\Models\User;
use Illuminate\Support\Collection;

class StaticPageService
{
    public function homeDataFor(?User $user): array
    {
        if (!$user) {
            return [];
        }

        /** @var Collection $microposts */
        $microposts = $user->feed();

        return [
            'microposts' => $microposts,
            'micropost' => new Micropost(),
        ];
    }
}

