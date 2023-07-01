<?php

namespace App\Domains\Article\Events;

use Carbon\CarbonImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasPublished extends ShouldBeStored
{
    public function __construct(
        public readonly string $uuid,
        public readonly bool $postToTwitter,
        public readonly ?CarbonImmutable $publishedAt,
    ) {
    }
}
