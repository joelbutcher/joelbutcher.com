<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Projections\Article;

class UpdateArticle
{
    public function __invoke(Article $article, ArticleData $articleData): Article
    {
        ArticleAggregate::retrieve($articleData->uuid)->updateArticle(
            articleData: $articleData,
        )->persist();

        $article = Article::uuid($articleData->uuid);

        return $article->refresh();
    }
}
