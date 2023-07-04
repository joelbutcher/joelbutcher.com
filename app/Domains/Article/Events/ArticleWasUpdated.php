<?php

namespace App\Domains\Article\Events;

use App\Domains\Article\Enums\Platform;
use Carbon\CarbonImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasUpdated extends ShouldBeStored
{
    /**
     * @param  array<string>  $tags
     * @param  array<Platform>  $platforms
     */
    public function __construct(
        public readonly string $uuid,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $series,
        public readonly string $excerpt,
        public readonly ?string $imagePath,
        public readonly ?string $content,
        public readonly CarbonImmutable $updatedAt,
        public readonly bool $published = false,
        public readonly array $tags = [],
        public readonly array $platforms = [],
        public readonly bool $postToTwitter = false,
    ) {
        collect($this->platforms)->each(
            fn ($platform) => assert($platform instanceof Platform)
        );
    }
}
