<?php

namespace App\Domains\Article\Reactors;

use App\Domains\Article\Actions\ShareArticle;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class ArticleReactor extends Reactor
{
    public function __construct(
        private readonly ShareArticle $shareArticle
    ) {
    }

    public function onArticleWasCreated(ArticleWasCreated $event): void
    {
        // We only care about sharing if the
        // article is being published.
        if (! $event->published) {
            return;
        }

        ($this->shareArticle)(
            article: $this->findArticle($event->articleUuid),
        );
    }

    public function onArticleWasUpdated(ArticleWasUpdated $event): void
    {
        // We only care about sharing if the
        // article is being published.
        if (! $event->published) {
            return;
        }

        $article = $this->findArticle($event->articleUuid);

        // Article has already been shared.
        if ($article->hasBeenShared()) {
            return;
        }

        // Article requires sharing.
        ($this->shareArticle)(
            article: $article
        );
    }

    private function findArticle(string $uuid): Article
    {
        return Article::uuid($uuid);
    }
}
