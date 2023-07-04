<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;

final readonly class DeleteArticle
{
    public function __invoke(string $uuid): void
    {
        ArticleAggregate::retrieve($uuid)
            ->delete()
            ->persist();
    }
}
