<?php

namespace App\Services\Twitter\DTOs;

use App\Http\Integrations\Twitter\Responses\SendTweetResponse;
use App\Services\Twitter\Casts\TweetCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;

readonly class Tweet implements Arrayable, Castable
{
    public function __construct(
        public ?string $id,
        public string $content,
    ) {
    }

    public static function fromResponse(SendTweetResponse $response): self
    {
        return new self(
            id: $response->id(),
            content: $response->content(),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            content: $data['content'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }

    public static function castUsing(array $arguments): string
    {
        return TweetCast::class;
    }
}
