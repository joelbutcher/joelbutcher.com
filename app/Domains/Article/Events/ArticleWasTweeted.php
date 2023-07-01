<?php

namespace App\Domains\Article\Events;

use App\Services\Twitter\DTOs\Tweet;
use Carbon\CarbonImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasTweeted extends ShouldBeStored
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $tweetId,
        public readonly string $tweetContent,
        public readonly ?CarbonImmutable $tweetedAt,
    ) {
    }

    public function tweet(): Tweet
    {
        return new Tweet(
            id: $this->tweetId,
            content: $this->tweetContent,
        );
    }
}
