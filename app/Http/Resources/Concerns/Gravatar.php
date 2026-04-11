<?php

namespace App\Http\Resources\Concerns;

trait Gravatar
{
    protected static function gravatarUrl(string $email, int $size = 80): string
    {
        $hash = md5(strtolower(trim($email)));

        return "https://secure.gravatar.com/avatar/{$hash}?s={$size}";
    }
}
