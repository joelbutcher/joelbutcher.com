<?php

namespace App\Domains\Article\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class ArticleWasDeleted extends ShouldBeStored
{
    public function __construct(
        public readonly string $uuid,
    ) {
    }
}
