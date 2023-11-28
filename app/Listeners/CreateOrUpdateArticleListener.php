<?php

namespace App\Listeners;

use App\Concerns\HandlesArticleEntries;
use App\Domains\Article\Actions\SaveArticle;
use App\Domains\Article\DataTransferObjects\ArticleData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Events\EntryCreated;
use Statamic\Events\EntrySaved;

class CreateOrUpdateArticleListener implements ShouldQueue
{
    use HandlesArticleEntries, InteractsWithQueue;

    public function __construct(
        private readonly SaveArticle $saveArticle
    ) {
    }

    public function handle(EntryCreated|EntrySaved $event): void
    {
        if (! $this->isValid($event)) {
            return;
        }

        ($this->saveArticle)(
            articleData: ArticleData::fromEntry($event->entry),
        );
    }
}
