<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;
use App\Services\Twitter\Contracts\TwitterServiceInterface;

final readonly class TweetArticle
{
    public function __construct(
        private TwitterServiceInterface $twitter,
    ) {
    }

    /**
     * @throws ArticleException
     */
    public function __invoke(Article $article): void
    {
        if (! $this->twitter->enabled()) {
            return;
        }

        ArticleAggregate::retrieve(
            uuid: $article->uuid,
        )->tweeted(
            tweet: $this->twitter->sendTweet(
                content: $article->composeTweet(),
            ),
        )->persist();
    }
}
