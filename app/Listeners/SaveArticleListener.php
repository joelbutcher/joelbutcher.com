<?php

namespace App\Listeners;

use App\Concerns\HandlesArticleEntries;
use App\Domains\Article\Actions\SaveArticle;
use App\Domains\Article\DTOs\ArticleData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Events\EntrySaved;

class SaveArticleListener implements ShouldQueue
{
    use InteractsWithQueue, HandlesArticleEntries;

    public function __construct(
        private readonly SaveArticle $saveArticle
    ) {
    }

    public function handle(EntrySaved $event): void
    {
        if (! $this->isValid($event)) {
            return;
        }

        ($this->saveArticle)(
            data: ArticleData::fromEntry($event->entry),
        );
    }
}
