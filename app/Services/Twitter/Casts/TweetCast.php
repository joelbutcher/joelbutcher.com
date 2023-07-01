<?php

namespace App\Services\Twitter\Casts;

use App\Services\Twitter\DTOs\Tweet;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TweetCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?Tweet
    {
        if (empty($value)) {
            return null;
        }

        assert(is_string($value));

        return Tweet::fromArray(
            data: json_decode($value, associative: true),
        );
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        assert($value instanceof Tweet);

        return json_encode($value->toArray());
    }
}
