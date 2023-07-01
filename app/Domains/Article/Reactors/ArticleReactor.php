<?php

namespace App\Domains\Article\Reactors;

use App\Domains\Article\Actions\PublishArticle;
use App\Domains\Article\Actions\TweetArticle;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use App\Services\Twitter\Facades\Twitter;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class ArticleReactor extends Reactor
{
    public function __construct(
        private readonly PublishArticle $publishArticle,
        private readonly TweetArticle $tweetArticle
    ) {
    }

    public function onArticleWasCreated(ArticleWasCreated $event): void
    {
        if (! $event->published) {
            return;
        }

        ($this->publishArticle)(
            article: Article::findByUuid($event->uuid),
        );
    }

    public function onArticleWasUpdated(ArticleWasUpdated $event): void
    {
        $article = Article::findByUuid($event->uuid);

        if ($this->shouldPublish($event, $article)) {
            ($this->publishArticle)(
                article: $article,
            );
        }

        if ($this->shouldTweet($event, $article)) {
            ($this->tweetArticle)(
                article: $article
            );
        }
    }

    private function shouldPublish(ArticleWasUpdated $event, ?Article $article): bool
    {
        if (! $event->published) {
            return false;
        }

        if ($article->hasBeenPublished()) {
            return false;
        }

        return true;
    }

    private function shouldTweet(ArticleWasUpdated $event, ?Article $article): bool
    {
        if (! Twitter::enabled()) {
            return false;
        }

        if (! $event->postToTwitter) {
            return false;
        }

        if ($article->hasBeenTweeted()) {
            return false;
        }

        return true;
    }
}
