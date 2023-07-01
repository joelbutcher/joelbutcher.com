<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;

final readonly class SaveArticle
{
    public function __construct(
        private CreateArticle $createArticle,
        private UpdateArticle $updateArticle,
        private PublishArticle $publishArticle,
    ) {
    }

    public function __invoke(ArticleData $data): void
    {
        $article = Article::findByUuid($data->uuid);

        $article
            ? $this->update($article, $data)
            : $this->create($data);
    }

    private function create(ArticleData $data): void
    {
        $article = ($this->createArticle)(
            data: $data
        );

        if ($this->shouldPublish($article, $data)) {
            $this->publish($article);
        }
    }

    private function update(Article $article, ArticleData $data): void
    {
        ($this->updateArticle)(
            data: $data,
        );

        if ($this->shouldPublish($article, $data)) {
            $this->publish($article);
        }
    }

    private function publish(Article $article): void
    {
        ($this->publishArticle)(
            article: $article,
        );
    }

    private function shouldPublish(Article $article, ArticleData $data): bool
    {
        return ! ($article->hasBeenPublished() || ! $data->published);
    }
}
