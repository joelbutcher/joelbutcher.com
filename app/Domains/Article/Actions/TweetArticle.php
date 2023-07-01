<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\TwitterManager;

final readonly class TweetArticle
{
    public function __construct(
        private TwitterManager $twitter,
    ) {
    }

    /**
     * @throws ArticleException
     */
    public function __invoke(Article $article): void
    {
        if (!$this->twitter->enabled()) {
            return;
        }

        ArticleAggregate::retrieve(
            uuid: $article->uuid,
        )->articleTeeeted(
            tweet: $this->tweet($article),
        )->persist();
    }

    private function tweet(Article $article): Tweet
    {
        return $this->twitter->sendTweet(
            content: $article->composeTweet(),
        );
    }
}
