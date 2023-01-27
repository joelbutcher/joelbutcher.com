<?php

namespace App\Domains\Article\Events;

use Carbon\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ArticleWasUpdated extends ShouldBeStored
{
    public function __construct(
        public readonly string $articleUuid,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $excerpt,
        public readonly array $content,
        public readonly bool $published,
        public readonly ?string $featuredImage,
        public readonly ?Carbon $updatedAt,
    ) {
    }
}
