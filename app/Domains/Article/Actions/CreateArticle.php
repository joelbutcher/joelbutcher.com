<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;

final readonly class CreateArticle
{
    public function __invoke(ArticleData $data): Article
    {
        ArticleAggregate::create(
            data: $data,
        )->persist();

        return Article::findByUuid($data->uuid);
    }
}
