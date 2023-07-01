<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DTOs\ArticleData;

final readonly class DeleteArticle
{
    public function __invoke(ArticleData $articleData): void
    {
        ArticleAggregate::retrieve($articleData->uuid)
            ->delete()
            ->persist();
    }
}
