<?php

namespace App\Listeners;

use App\Concerns\HandlesArticleEntries;
use App\Domains\Article\Actions\DeleteArticle;
use App\Domains\Article\Projections\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Entries\Entry;
use Statamic\Events\EntryDeleted;

class DeleteArticleListener implements ShouldQueue
{
    use HandlesArticleEntries, InteractsWithQueue;

    public function __construct(
        private readonly DeleteArticle $deleteArticle,
    ) {
    }

    public function handle(EntryDeleted $event): void
    {
        if (! $this->isValid($event)) {
            return;
        }

        /** @var Entry $entry */
        $entry = $event->entry;

        ($this->deleteArticle)(
            article: Article::uuid($entry->id()),
        );
    }
}
