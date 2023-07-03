<?php

namespace App\Domains\Article\Events;

use App\Domains\Article\Enums\Platform;
use Carbon\CarbonImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasPublished extends ShouldBeStored
{
    /**
     * @var Platform[]
     */
    public readonly array $platforms;

    public function __construct(
        public readonly string $uuid,
        public readonly ?CarbonImmutable $publishedAt,
        Platform...$platforms
    ) {
        $this->platforms = $platforms;
    }
}
