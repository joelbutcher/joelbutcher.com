<?php

namespace App\Domains\Article\Casts;

use App\Domains\Article\Enums\Platform;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PlatformsCast implements CastsAttributes
{
    /**
     * @param string $value
     * @return Collection<Platform>
     */
    public function get($model, string $key, $value, array $attributes): Collection
    {
        if (empty($value)) {
            return [];
        }

        assert(is_string($value));

        return collect(json_decode($value, associative: true))->map(
            callback: fn (string $platform) => Platform::from($platform),
        );
    }

    /**
     * @param array<Platform> $value
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        assert(is_array($value) || $value instanceof Collection);

        $value = $value instanceof Collection ? $value->toArray() : Arr::wrap($value);

        return json_encode(
            array_filter(array_map(
                callback: fn (Platform $platform) => $platform->value,
                array: $value,
            )),
        );
    }
}
