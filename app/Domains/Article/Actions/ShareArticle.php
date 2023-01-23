<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;

class ShareArticle
{
    /**
     * @throws ArticleException
     */
    public function __invoke(
        Article $article,
    ): Article {
        ArticleAggregate::retrieve(
            uuid: $article->uuid,
        )->shareArticle()->persist();

        return $article->refresh();
    }
}
