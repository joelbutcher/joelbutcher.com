<?php

namespace App\Services\Twitter;

use App\Domains\Article\Projections\Article;

class TwitterRemoveTweet implements TwitterAction
{
    public function __construct(
        public readonly string $tweetId
    ) {
    }

    public static function forArticle(Article $article): self
    {
        return new self(
            tweetId: $article->tweet_id,
        );
    }

    public function apiPath(): string
    {
        return 'statuses/destroy';
    }

    public function requestBody(): array
    {
        return [];
    }
}
