<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;

final readonly class PublishArticle
{
    /**
     * @throws ArticleException
     */
    public function __invoke(
        Article $article,
    ): void {
        ArticleAggregate::retrieve(
            uuid: $article->uuid,
        )->publish()->persist();
    }
}
