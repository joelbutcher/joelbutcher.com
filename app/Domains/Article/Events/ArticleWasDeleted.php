<?php

namespace App\Domains\Article\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ArticleWasDeleted extends ShouldBeStored
{
    public function __construct(
        public readonly string $articleUuid,
    ) {
    }
}
