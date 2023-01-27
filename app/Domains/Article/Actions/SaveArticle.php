<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Projections\Article;

class SaveArticle
{
    public function __construct(
        private CreateArticle $createArticle,
        private UpdateArticle $updateArticle,
    ) {
    }

    public function __invoke(ArticleData $articleData): void
    {
        $article = Article::uuid($articleData->uuid);

        $article
            ? $this->update(article: $article, articleData: $articleData)
            : $this->create(articleData: $articleData);
    }

    private function create(ArticleData $articleData): void
    {
        ($this->createArticle)(
            articleData: $articleData
        );
    }

    private function update(Article $article, ArticleData $articleData): void
    {
        ($this->updateArticle)(
            article: $article,
            articleData: $articleData,
        );
    }
}
