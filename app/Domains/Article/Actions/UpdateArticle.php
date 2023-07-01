<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DTOs\ArticleData;

final readonly class UpdateArticle
{
    public function __invoke(ArticleData $articleData): void
    {
        ArticleAggregate::retrieve($articleData->uuid)->update(
            articleData: $articleData,
        )->persist();
    }
}
