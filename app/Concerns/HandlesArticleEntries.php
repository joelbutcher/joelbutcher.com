<?php

namespace App\Concerns;

use App\Enums\CollectionHandle;
use Statamic\Entries\Entry;
use Statamic\Events\Event;

trait HandlesArticleEntries
{
    public function isValid(Event $event): bool
    {
        $entry = $event->entry;

        return $entry instanceof Entry
            && $entry->collectionHandle() === CollectionHandle::Articles->value;
    }
}
