<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;

final readonly class SaveArticle
{
    public function __construct(
        private CreateArticle $createArticle,
        private UpdateArticle $updateArticle,
    ) {
    }

    public function __invoke(ArticleData $articleData): void
    {
        $article = Article::findByUuid($articleData->uuid);

        $article
            ? $this->update(articleData: $articleData)
            : $this->create(articleData: $articleData);
    }

    private function create(ArticleData $articleData): void
    {
        ($this->createArticle)(
            articleData: $articleData
        );
    }

    private function update(ArticleData $articleData): void
    {
        ($this->updateArticle)(
            articleData: $articleData,
        );
    }
}
