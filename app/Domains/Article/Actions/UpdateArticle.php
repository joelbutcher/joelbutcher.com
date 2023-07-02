<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DTOs\ArticleData;

final readonly class UpdateArticle
{
    public function __invoke(ArticleData $data): void
    {
        ArticleAggregate::retrieve($data->uuid)->update(
            data: $data,
        )->persist();
    }
}
