<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Projections\Article;

class DeleteArticle
{
    public function __invoke(Article $article): void
    {
        ArticleAggregate::retrieve($article->uuid)
            ->delete()
            ->persist();
    }
}
