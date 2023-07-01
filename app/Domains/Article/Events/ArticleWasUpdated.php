<?php

namespace App\Domains\Article\Events;

use Carbon\CarbonImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasUpdated extends ShouldBeStored
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $excerpt,
        public readonly ?string $content,
        public readonly CarbonImmutable $updatedAt,
        public readonly bool $published = false,
        public array $tags = [],
        public array $platforms = [],
        public bool $postToTwitter = false,
    ) {
    }
}
