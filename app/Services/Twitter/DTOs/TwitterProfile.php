<?php

namespace App\Services\Twitter\DTOs;

use App\Http\Integrations\Twitter\Responses\GetProfileResponse;
use Illuminate\Contracts\Support\Arrayable;

final readonly class TwitterProfile implements Arrayable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $username,
    ) {
    }

    public static function fromResponse(GetProfileResponse $response): self
    {
        return new self(
            id: $response->id(),
            name: $response->name(),
            username: $response->username(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
        ];
    }
}
