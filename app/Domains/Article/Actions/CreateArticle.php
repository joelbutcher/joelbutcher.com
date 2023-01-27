<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Projections\Article;

class CreateArticle
{
    public function __invoke(ArticleData $articleData): Article
    {
        ArticleAggregate::createArticle(
            articleData: $articleData,
        )->persist();

        $articleData = Article::uuid($articleData->uuid);

        return $articleData->fresh();
    }
}
