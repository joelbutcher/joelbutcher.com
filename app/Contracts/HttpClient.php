<?php

namespace App\Contracts;

use Illuminate\Http\Client\PendingRequest;

/**
 * @mixin PendingRequest
 */
interface HttpClient
{
    public static function from(
        string $baseUrl,
        string $token,
    ): self;

    public function __call(string $name, array $arguments);
}
