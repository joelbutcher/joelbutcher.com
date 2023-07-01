<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DTOs\ArticleData;

final readonly class CreateArticle
{
    public function __invoke(ArticleData $articleData): void
    {
        ArticleAggregate::create(
            articleData: $articleData,
        )->persist();
    }
}
