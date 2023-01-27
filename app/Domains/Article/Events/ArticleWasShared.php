<?php

namespace App\Domains\Article\Events;

use Carbon\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ArticleWasShared extends ShouldBeStored
{
    public function __construct(
        public readonly string $articleUuid,
        public readonly ?Carbon $sharedAt,
    ) {
    }
}
